<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Manager
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards <geolysis@zoho.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Manager;

use Gz3Base\Mvc\Controller\AbstractActionController;
use Gz3Base\Mvc\Controller\NoopController;
use Gz3Base\Mvc\Service\AbstractService;


class NoopManager extends AbstractManager
{

    /** @static DELETE = false */
    /** @static ARCHIVED_TABLE_PREFIX = 'archived_' */

    /** @var AbstractActionController self::$controller */

    /** @var string $this->entityType */
    /** @var array $this->attributeTypes; */
    /** @var array $this->data; */
    /** @var bool $this->isPersistent */
    /** @var TableMap $this->tableMap */
    /** @var Adapter $this->adapter */
    /** @var TableGateway $this->tableGateway */
    /** @var RowGateway $this->rowGateway */
    /** @var Sql $this->sql */


    /**
     * @param AbstractActionController $controller
     * @return AbstractManager $this
     */
    public function initialise(AbstractActionController $controller) : AbstractManager
    {
        self::$controller = new NoopController();

        return $this;
    }

    /**
     * @return string $entityType
     */
    public function getEntityType() : string
    {
        return 'NoopEntity';
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
        return false;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return array $readData
     */
    protected function read(string $field, $value) : array
    {
        return [];
    }

    /**
     * @return bool $successfulUpdate
     */
    protected function update() : bool
    {
        return false;
    }

    /**
     * @return $successfulDelete
     */
    public function delete() : bool
    {
        return false;
    }

    /**
     * @return $successfulDeactivate
     */
    public function deactivate() : bool
    {
        return false;
    }

    /**
     * @return $successfulArchive
     */
    public function archive() : bool
    {
        return false;
    }

}
