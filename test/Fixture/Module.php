<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3BaseTest\Fixture
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3BaseTest\Fixture;

use Gz3Base\AbstractModule;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;


class Module extends AbstractModule
{

    /** @var \ReflectionClass $this->reflection */
    /** @var string $this->namespace */
    /** @var string $this->directory */


    /**
     * {@inheritDoc}
     * @see \Gz3Base\AbstractModule::init()
     */
    public function init() {}

    /**
     * {@inheritDoc}
     * @see \Gz3Base\AbstractModule::getNamespace()
     */
    protected function getNamespace() : string
    {
        parent::getNamespace();
        $namespace = preg_replace('#\\\\+Fixture#', '', $this->namespace);

        return $namespace;
    }

    /**
     * {@inheritDoc}
     * @see \Gz3Base\AbstractModule::getDirectory()
     */
    protected function getDirectory() : string
    {
        parent::getDirectory();
        $directory = strstr($this->directory, DIRECTORY_SEPARATOR.'test', true);

        return $directory;
    }

    /**
     * {@inheritDoc}
     * @see \Gz3Base\AbstractModule::getNamespaceAutoloaderPath()
     */
    protected function getNamespaceAutoloaderPath()
    {
        return $this->getDirectory().'/test/'.$this->getNamespace();
    }

}
