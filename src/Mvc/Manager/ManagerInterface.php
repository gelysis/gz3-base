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
use Gz3Base\Mvc\Service\ConfigService;
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
    public function getConfigService() : ConfigService;


    /**
     * @return RecordService $this->recordService
     */
    public function getRecordService() : RecordService;

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
    protected function read(string $field, $value);

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
