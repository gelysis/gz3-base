<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
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
     * @param string[] $moduleTestPaths
     */
    public static function init()
    {
        $modules = $moduleTestPaths = array();
        $loader = new StandardAutoloader();
        $applicationConfig = self::getApplicationConfig();

        foreach ($applicationConfig['module_listener_options']['module_paths'] as $modulePath) {
            foreach ($applicationConfig['modules'] as $module) {
                $testPath = realpath($modulePath.'/'.$module.'/test');
                if ($testPath) {
                    $loader->registerNamespace($module.'Test', $testPath.'/'.$module);
                    $modules[] = $module;
                }
            }
        }

        self::registerAddtionalNamespace($loader);
        $loader->register();

        $applicationConfig['modules'] = $modules;

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $applicationConfig);
        $serviceManager->get('ModuleManager')->loadModules();

        static::$serviceManager = $serviceManager;
    }

    /**
     * @return array $applicationConfig
     */
    public static function getApplicationConfig()
    {
        $root = self::getRootPath();
        $applicationConfig = include $root.'config/application.config.php';

        return $applicationConfig;
    }

    /**
     * @return array $applicationConfig
     */
    public static function getRootPath()
    {
        return strstr(__DIR__, 'vendor/', true);
    }

    /**
     * @param StandardAutoloader &$loader
     * @return StandardAutoloader $loader
     */
    protected static function registerAddtionalNamespace(StandardAutoloader &$loader) : StandardAutoloader
    {
        $loader->registerNamespace('Gz3Base\\Test', __DIR__);
        return $loader;
    }

    /**
     * @return ServiceManager static::$serviceManager
     */
    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

}
