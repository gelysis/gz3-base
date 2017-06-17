<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Service
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Service;

use Gz3Base\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Gz3Base\Mvc\Controller\NoopController;


trait ServiceTrait
{

    /** @var AbstractActionController self::$controller */
    protected static $controller = null;
    /** @var string[] self::$routeParameters */
    protected static $routeParameters;
    /** @var string[] self::$defaultMethodPrefixes */
    protected static $defaultMethodPrefixes = ['is', 'set', 'get', 'add', 'has'];
    /** @var string[] $this->additionalMethodPrefixes */
    protected $additionalMethodPrefixes = [];


    /**
     * @param AbstractActionController $controller
     * @return AbstractService $this
     */
    public function setController(AbstractActionController $controller) : AbstractService
    {
        self::$controller = $controller;

        return $this;
    }

    /**
     * @return AbstractActionController $controller
     */
    protected function getController() : AbstractActionController
    {
        if (is_null(self::$controller)) {
            $controller = new NooptController();
        }else {
            $controller = self::$controller;
        }

        return $controller;
    }

    /**
     * @return array $routeParameters
     */
    protected function getRouteParameters() : array
    {
        if (is_null(self::$routeParameters)) {
            self::$routeParameters = $this->getController()->getRouteParameters();
        }

        return self::$routeParameters;
    }

    /**
     * @param string $prefix
     * @return bool $successfulAdded
     */
    protected function addMethodPrefixes(string $prefix) : bool
    {
        $this->methodPrefixes[] = $prefix;

        return (in_array($prefix, $this->methodPrefixes));
    }

    /**
     * @return string[] self::defaultMethodPrefixes
     */
    public static function getDefaultMethodPrefixes() : array
    {
        return self::$defaultMethodPrefixes;
    }

    /**
     * @return string[] $methodPrefixes
     */
    protected function getMethodPrefixes() : array
    {
        $methodPrefixes = array_unique(array_merge(self::getDefaultMethodPrefixes(), $this->additionalMethodPrefixes));

        return $methodPrefixes;
    }

}
