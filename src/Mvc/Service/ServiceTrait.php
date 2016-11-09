<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Service
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards <geolysis@zoho.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
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


    /**
     * @param AbstractActionController $controller
     * @return ServiceInterface $this
     */
    public function setController(AbstractActionController $controller) : ServiceInterface
    {
        self::$controller = $controller;

        return $this;
    }

    /**
     * @return AbstractActionController $controller
     */
    public function getController() : AbstractActionController
    {
        if (is_null(self::$controller)) {
            $controller = new NooptController();
        }else{
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

}
