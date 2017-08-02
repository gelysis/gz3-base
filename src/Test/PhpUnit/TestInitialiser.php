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
     * @param array $applicationConfig
     * @param string[] $modulePaths
     */
    public static function init(array $applicationConfig)
    {
        $modules = [];
        $loader = new StandardAutoloader();

        $modulePaths = [];
        foreach ($applicationConfig['module_listener_options']['module_paths'] as $modulePath) {
            foreach ($applicationConfig['modules'] as $key=>$module) {
                $rootSpace = strstr($module, '\\', true);
                $relativePath = str_replace([$rootSpace.'\\', '\\'], ['', DIRECTORY_SEPARATOR], $module);
                $isTestSpace = preg_match('#tests?#ism', (string) $key) || preg_match('#/tests?$#sm', $modulePath);

                if ($isTestSpace) {
                    $module = str_replace($rootSpace, $rootSpace.'Test', $module);
                }

                $fullPath = realpath($modulePath.DIRECTORY_SEPARATOR.$relativePath);
                if ($fullPath) {
                    $modulePaths[$module] = $fullPath;
                }
            }
        }

        foreach ($modulePaths as $module=>$path) {
            $modules[] = $module;
            $loader->registerNamespace($module.'Test', $path.'/'.$module);
        }
        $loader->register();

        $applicationConfig['modules'] = $modules;
        self::setServiceManager($applicationConfig);

        self::getServiceManager()->get('ModuleManager')->loadModules();
    }

    /**
     * @param array $config
     * @return void
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
