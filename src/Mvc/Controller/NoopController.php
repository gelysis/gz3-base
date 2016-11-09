<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Controller
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Controller;

use Gz3Base\Mvc\Entity\AbstractEntity;
use Gz3Base\Mvc\Entity\NoopEntity;
use Gz3Base\Mvc\Service\AbstractService;
use Gz3Base\Mvc\Service\NoopService;
use Zend\Di\ServiceLocatorInterface;


class NoopController extends AbstractActionController
{

    const INIT_RECORDING = false;
    const DEINIT_RECORDING = false;

    /** @var AbstractService[] self::$services */
    /** @var ServiceLocatorInterface $this->serviceLocator */
    /** @var array $this->routeParameters */
    /** @var \ReflectionClass $reflectionClass */
    /** @var string $this->recordIdPrefix */
    /** @var array $this->methodName */
    /** @var array $methodStart */


    /**
     * {inheritDoc}
     * @see \Gz3Base\Mvc\Controller\AbstractActionController::getService()
     * @return AbstractService $noopService
     */
    protected function getService(string $serviceCode) : AbstractService
    {
        return new NoopService();
    }

    /**
     * {inheritDoc}
     * @see \Gz3Base\Mvc\Controller\AbstractActionController::getRecordService()
     * @return AbstractService $noopService
     */
    protected function getRecordService() : AbstractService
    {
        return new NoopService();
    }

    /**
     * {inheritDoc}
     * @see \Gz3Base\Mvc\Controller\AbstractActionController::record()
     */
    public function record(string $id, int $priority, string $message, array $data = array()) : bool
    {
        return false;
    }

    /**
     * {inheritDoc}
     * @see \Gz3Base\Mvc\Controller\AbstractActionController::getConfigService()
     * @return AbstractService $noopService
     */
    public function getConfigService() : AbstractService
    {
        return new NoopService();
    }

    /**
     * {inheritDoc}
     * @see \Gz3Base\Mvc\Controller\AbstractActionController::getEntity()
     * @return AbstractEntity $noopEntity
     */
    public function getEntity(string $entityType) : AbstractEntity
    {
        return new NoopEntity();
    }

    /**
     * {inheritDoc}
     * @see \Gz3Base\Mvc\Controller\AbstractActionController::getRouteParameters()
     */
    public function getRouteParameters()
    {
        return [];
    }

}
