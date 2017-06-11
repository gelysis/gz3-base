<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;


abstract class AbstractModule
{

    /** @var \ReflectionClass $this->reflection */
    protected $reflection;
    /** @var string $this->namespace */
    protected $namespace;
    /** @var string $this->directory */
    protected $directory;


    /**
     * @return void
     */
    public function __construct()
    {
        $this->reflection = new \ReflectionClass($this);
    }

    /**
     * Module initialisation
     * @return void
     */
    abstract public function init();

    /**
     * Called after init()
     * @param MvcEvent $event
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        $eventManager = $event->getApplication()
           ->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @return string $classNamespace
     */
    protected function getNamespace() : string
    {
        if (is_null($this->namespace)) {
            $this->namespace = $this->reflection->getNamespaceName();
        }

        return $this->namespace;
    }

    /**
     * @return string $classFileDirectory
     */
    protected function getDirectory() : string
    {
        if (is_null($this->directory)) {
            $this->directory = dirname($this->reflection->getFileName());
        }

        return $this->directory;
    }

    /**
     * Defines src/<Namespace> as standard code sub-directory
     * @return string[][][] $autoloaderConfig
     */
    public function getAutoloaderConfig() : array
    {
        $autoloderConfig = [
            'Zend\Loader\StandardAutoloader'=>[
                'namespaces'=>[
                    $this->getNamespace()=>$this->getDirectory().'/src/'.$this->getNamespace()
                ]
            ]
        ];

        return $autoloderConfig;
    }

    /**
     * Includes config files in the following order: module.config.php, module.*.php, module.local.php
     * @return array $moduleConfig
     */
    public function getConfig() : array
    {
        $moduleConfig = $this->getDirectory().'/config/module.config.php';
        if (file_exists($moduleConfig)) {
            $moduleConfig = include $moduleConfig;
            $localConfig = $this->getDirectory().'/config/module.local.php';

            $handle = opendir($this->getDirectory().'/config');
            while ($configFile = readdir($handle)) {
                $isModuleConfigFile = preg_match('#module\.(\w+)\.php#ism', $configFile, $match);
                $includeModuleConfigFile = $isModuleConfigFile && !in_array($match[1], ['config', 'local']);

                if ($includeModuleConfigFile) {
                    $moduleConfig = array_replace_recursive(
                        $moduleConfig,
                        include $this->getDirectory().'/config/'.$configFile
                    );
                }
            }
            closedir($handle);

            if (file_exists($localConfig)) {
                $localConfig = include $localConfig;
                $moduleConfig = array_replace_recursive($moduleConfig, $localConfig);
            }
        }else {
            throw new FileException('Configuration file '.$moduleConfig.' does not exist.');
        }

        return $moduleConfig;
    }

}
