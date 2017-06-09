<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Controller
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Controller;

use Gz3Base\Mvc\Entity\NoopEntity;
use Gz3Base\Mvc\Service\NoopService;
use Gz3Base\Mvc\Service\ServiceInterface;


class NoopController extends AbstractActionController
{

    /** @var bool self::INIT_RECORDING */
    const INIT_RECORDING = false;
    /** @var bool self::DEINIT_RECORDING */
    const DEINIT_RECORDING = false;

    /** @var BaseService[] self::$services */

    /** @var ServiceLocatorInterface $this->serviceLocator */
    /** @var array $this->routeParameters */
    /** @var \ReflectionClass $reflectionClass */
    /** @var string $this->recordIdPrefix */
    /** @var array $this->methodName */
    /** @var array $this->methodStart */


    /**
     * {inheritDoc}
-    * @see \Gz3Base\Mvc\Controller\AbstractActionController::getService()
     */
    protected function getService(string $serviceCode) : ServiceInterface
    {
        return new NoopService();
    }

    /**
     * {@inheritDoc}
     * @see \Gz3Base\Mvc\Controller\AbstractActionController::getRecordService()
     */
    protected function getRecordService() : ServiceInterface
    {
        return new NoopRecordService();
    }

    /**
     * @param string $entityType
     * @return AbstractEntity $noopEntity
     */
    public function getEntity(string $entityType) : AbstractEntity
    {
        return new NoopEntity();
    }

}
