<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 */

declare(strict_types = 1);
namespace Gz3Base\Test\PhpUnit;

use Zend\Loader\StandardAutoloader;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\Util\ModuleLoader;


class TestInitialiser
{

    /** @var ServiceManager self::$serviceManager */
    protected static $serviceManager;


    /**
     * @param array $config
     * @param string[] $moduleTestPaths
     */
    public static function init(array $config, array $moduleTestPaths)
    {
        $modules = [];
        $loader = new StandardAutoloader();

        foreach ($moduleTestPaths as $module=>$path) {
            $modules[] = $module;
            $loader->registerNamespace($module.'Test', $path.'/'.$module);
        }
        $loader->register();

        $config['modules'] = $modules;
        self::setServiceManager($config);

        self::getServiceManager()->get('ModuleManager')->loadModules();
    }

    /**
     * @param array $config
     */
    public static function setServiceManager(array $config)
    {
        $serviceManager = new ServiceManager($config);
        $serviceManager->setService('ConfigService', $config);
        self::$serviceManager = $serviceManager;
    }

    /**
     * @return ServiceManager static::$serviceManager
     */
    public static function getServiceManager() : ServiceManager
    {
        return self::$serviceManager;
    }

}
