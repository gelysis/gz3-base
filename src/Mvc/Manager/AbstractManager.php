<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Manager
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Manager;

use Gz3Base\Mvc\Controller\AbstractActionController;
use Gz3Base\Mvc\Service\AbstractService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\RowGateway\RowGateway;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\Db\TableGateway\TableGateway;
use Propel\Runtime\Map\TableMap;


abstract class AbstractManager implements ManagerInterface
{

    /** @var bool self::DELETE */
    const DELETE = false;
    /** @var string self::ARCHIVED_TABLE_PREFIX */
    const ARCHIVED_TABLE_PREFIX = 'archived_';

    /** @var AbstractActionController self::$controller */
    protected static $controller;

    /** @var string $this->entityType */
    protected $entityType;
    /** @var array $this->attributeTypes; */
    protected $attributeTypes = [];
    /** @var array $this->data; */
    protected $data = [];
    /** @var bool $this->isPersistent */
    protected $isPersistent = false;
    /** @var TableMap $this->tableMap */
    protected $tableMap;
    /** @var Adapter $this->adapter */
    protected $adapter;
    /** @var TableGateway $this->tableGateway */
    protected $tableGateway;
    /** @var RowGateway $this->rowGateway */
    protected $rowGateway;
    /** @var Sql $this->sql */
    protected $sql;


    /**
     * @param AbstractActionController $controller
     * @return AbstractManager $this
     */
    public function initialise(AbstractActionController $controller) : AbstractManager
    {
        self::$controller = $controller;

        $this->adapter = new Adapter($dbDetails);
        $this->tableGateway = new TableGateway($this->getEntityType(), $adapter, RowGatewayFeature('id'));
        $this->sql = new Sql($this->adapter);

        return $this;
    }

    /**
     * @return AbstractService $this->configService
     */
    protected function getConfigService() : AbstractService
    {
        return self::$controller->getConfigService();
    }

    /**
     * @return AbstractService $recordService
     */
    protected function getRecordService() : AbstractService
    {
        return self::$controller->getRecordService();
    }

    /**
     * @return string $entityType
     */
    public function getEntityType() : string
    {
        // @todo: Implement functionality, if null get entity type : trait ?
        return $this->entityType;
    }

    /**
     * @return string $archivedTablename
     */
    public function getArchivedTablename() : string
    {
        return self::ARCHIVED_TABLE_PREFIX.$this->getEntityType();
    }

    /**
     * @return bool $successfulSave
     */
    public function save() : bool
    {
        if ($this->isPersistent) {
            $success = $this->update();
        }else {
            $success = $this->create();
        }
        $this->isPersistent = $this->isPersistent || $success;

        return $success;
    }

    /**
     * @param int $id
     * @return array $data
     */
    public function load(int $id) : array
    {
        return $this->loadBy('id', $id);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return array $data
     */
    public function loadBy(string $field, $value) : array
    {
        return $this->read($field, $value);
    }

    /**
     * @return bool $successfulCreate
     */
    protected function create() : bool
    {
        $insert = $this->sql->insert($this->getTablename());
        $id = $this->adapter->getDriver()
            ->getConnection()
            ->getLastGeneratedValue();
        $this->load($id);

        $success = true;

        return $success;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return array $readData
     */
    protected function read(string $field, $value) : array
    {
        $row = [];
        // @todo
        $this->isPersistent = (is_array($row) && count($row) > 0);

        $resultSet = $this->tableGateway->select([
            $field=>$value
        ]);
        $this->rowGateway = $resultSet->current();

        return $this->rowGateway->getArrayCopy();
    }

    /**
     * @return bool $successfulUpdate
     */
    protected function update() : bool
    {
        $success = (bool) $this->rowGateway->save();
        return $success;
    }

    /**
     * @return $successfulDelete
     */
    public function delete() : bool
    {
        if (static::DELETE) {
            $sucess = (bool) $this->rowGateway->delete();
        }else {
            $success = false;        }

        return $success;
    }

    /**
     * @return $successfulDeactivate
     */
    public function deactivate() : bool
    {
        if (array_key_exists('active', $this->attributeTypes)) {
            $this->data['active'] = false;
            $success = $this->save();
        }else {
            $success = false;
        }

        return $success;
    }

    /**
     * @return $successfulArchive
     */
    public function archive() : bool
    {
        if ($this->isArchivedTable()) {
            // @todo: Replace pseudo code
            $archived = ! $this->sql->create($this->getArchivedTablename());
            if ($archived) {
                $archived = $archived && $this->delete();
                if ($archived) {
                    $duplicate = false;
                }else {
                    $duplicate = ! $this->sql->delete($this->getArchivedTablename());
                }
            }
        }else {
            $archived = false;
        }

        if ($archived) {
            $this->record('arc_suc', RecordService::INFO, 'Archived entity successfully.', $this->data);
        }elseif ($duplicate) {
            $this->record('arc_err', RecordService::ERROR, 'Archiving ended up with duplicate data sets. ', $this->data);
        }else {
            $this->record('arc_fai', RecordService::ERROR, 'Archiving failed.', $this->data);
        }

        return $archived;
    }

}
