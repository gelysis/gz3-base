<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright Copyright ©2016 Andreas Gerhards
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\ServiceManager;

use Gz3Base\Mvc\Controller\AbstractActionController;
use Gz3Base\Mvc\Service\AbstractService;
use Gz3Base\Mvc\Service\ConfigService;
use Gz3Base\Mvc\Service\ServiceInterface;
use Gz3Base\Record\Service\AbstractRecordService;

use Zend\ServiceManager\Initializer\InitializerInterface;
use Interop\Container\ContainerInterface;


class Initialiser implements InitializerInterface
{

    protected static $configuration = null;


    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Initializer\InitializerInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        $initialise = false;

        if (is_array($instance)) {
            self::$configuration = $instance;
        }

        if ($instance instanceof AbstractService) {
            if ($instance instanceof ConfigService) {
                $instance->setConfiguration(self::$configuration);
                $initialise = true;
            }
            if ($instance instanceof AbstractManager) {
                $instance->setConfigService($container->get('Service\Config'));
                $initialise = true;
            }
        }

        if ($instance instanceof AbstractRecordService) {
            $instance->setThreadIdentifier('_'.dechex(rand(0x000, 0xFFF)));
            $initialise = true;
        }

        if ($instance instanceof AbstractActionController || $instance instanceof ServiceInterface) {
            if (method_exists($instance, 'setServiceLocator')) {
                $instance->setServiceLocator($container);
                $initialise = true;
            }
            if (method_exists($instance, 'initialise')) {
                $initialise |= $instance->initialise();
            }
        }

        return (bool) $initialise;
    }

}
