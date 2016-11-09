<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards <geolysis@zoho.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base;


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
     * @see \Gz3Base\AbstractModule::getAutoloaderConfig()
     */
    public function getAutoloaderConfig() : array
    {
        return [
            'Zend\Loader\StandardAutoloader'=>[
                'namespaces'=>[
                    __NAMESPACE__=>__DIR__.'/src/',
                ]
            ]
        ];
    }

}
