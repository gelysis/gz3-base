<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\ServiceManager;

use Gz3Base\Mvc\Controller\AbstractActionController;
use Gz3Base\Mvc\Manager\AbstractManager;
use Gz3Base\Mvc\Service\AbstractService;
use Gz3Base\Mvc\Service\ConfigService;
use Gz3Base\Mvc\Service\ServiceInterface;
use Gz3Base\Record\RecordableInterface;
use Gz3Base\Record\Service\RecordService;
use Zend\ServiceManager\Initializer\InitializerInterface;
use Interop\Container\ContainerInterface;


class Initialiser implements InitializerInterface
{

    /** @var array self::$config */
    protected static $config = null;


    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\Initializer\InitializerInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        $initialise = false;

        if (is_array($instance)) {
            self::$config = $instance;
        }

        if ($instance instanceof AbstractActionController) {
            $instance->setServiceLocator($container);
            $initialise |= true;
        }

        if ($instance instanceof ConfigService) {
            $instance->setConfig(self::$config);
            $initialise |= true;
        }

        if ($instance instanceof RecordService) {
            $instance->setThreadIdentifier('_'.dechex(rand(0x000, 0xFFF)));
            $initialise |= true;
        }

        if (method_exists($instance, 'initialise')) {
            $initialise |= $instance->initialise();
        }

        return (bool) $initialise;
    }

}
