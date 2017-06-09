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
use Gz3Base\Mvc\Service\ServiceInterface;
use Gz3Base\Record\Service\RecordService;


interface ManagerInterface
{

    /**
     * @param AbstractActionController $controller
     * @return ManagerInterface
     */
    public function setController(AbstractActionController $controller) : ManagerInterface;

    /**
     * @return ConfigService $configService
     */
    public function getConfigService() : AbstractService;


    /**
     * @return RecordService $this->recordService
     */
    public function getRecordService() : ServiceInterface;

    /**
     * @return string $this->entityType
     */
    public function getEntityType() : string;

    /**
     * @return string $archivedTablename
     */
    public function getArchivedTablename() : string;

    /**
     * @return ManagerInterface $this
     */
    public function initialise() : ManagerInterface;

    /**
     * @return bool $successfulSaved
     */
    public function save() : bool;

    /**
     * @param int $id
     * @return array $data
     */
    public function load(int $id) : array;

    /**
     * @param string $field
     * @param mixed $value
     * @return array $data
     */
    public function loadBy(string $field, $value) : array;

    /**
     * @return bool $successfulCreate
     */
    protected function create() : bool;

    /**
     * @param string $field
     * @param mixed $value
     * @return array $readData
     */
    protected function read(string $field, $value) : array;

    /**
     * @return bool $successfulUpdate
     */
    protected function update() : bool;

    /**
     * @return $successfulDelete
     */
    public function delete() : bool;

    /**
     * @return $successfulDeactivate
     */
    public function deactivate() : bool;

    /**
     * @return $successfulArchive
     */
    public function archive() : bool;

}
