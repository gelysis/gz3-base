<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 */

namespace Gz3Base\Test\PhpUnit;

use Zend\Loader\StandardAutoloader;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;


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
        $modules = array();
        $loader = new StandardAutoloader();

        foreach ($moduleTestPaths as $module=>$path) {
            $modules[] = $module;
            $loader->registerNamespace($module.'Test', $path.'/'.$module);
        }

        $loader->registerNamespace('Gz3Base\\Test', __DIR__);
        $loader->register();

        $config['modules'] = $modules;

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        static::$serviceManager = $serviceManager;
    }

    /**
     * @return ServiceManager static::$serviceManager
     */
    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

}
