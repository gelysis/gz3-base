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


interface ManagerInterface
{

    /**
     *
     * @param AbstractActionController $controller
     * @return ManagerInterface
     */
    public function setController(AbstractActionController $controller) : ManagerInterface;

    /**
     * @return \Gz3Base\Mvc\Service\ConfigService $configService
     */
    public function getConfigService() : AbstractService;

    /**
     * @return \Gz3Base\Record\Service\RecordService $recordService
     */
    public function getRecordService() : ServiceInterface;

    /**
     * @return string $entityType
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
     * @return bool $successfullyCreated
     */
    protected function create() : bool;

    /**
     * @param string $field
     * @param mixed $value
     * @return array $readData
     */
    protected function read(string $field, $value) : array;

    /**
     * @return bool $successfullyUpdated
     */
    protected function update() : bool;

    /**
     * @return $successfullyDeleted
     */
    public function delete() : bool;

    /**
     * @return $successfullyDeactivated
     */
    public function deactivate() : bool;

    /**
     * @return $successfullyArchived
     */
    public function archive() : bool;

}
