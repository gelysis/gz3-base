<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

namespace Gz3Base\Test\PhpUnit\Model;

use Gz3Base\Mvc\Service\AbstractService;
use Gz3Base\Mvc\Exception\ServiceNotFoundException;


class TestCase extends \PHPUnit_Framework_TestCase
{

    /** @var mixed $this->objectToTest */
    protected $objectToTest = null;
    /** @var \ReflectionClass $this->objectReflectionClass */
    protected $objectReflectionClass = null;


    /**
     * {@inheritDoc}
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $reflectionClass = new \ReflectionClass($this);
        try {
            $serviceToTest = strtolower(strstr($reflectionClass->getShortName(), 'Test', true));
            $this->objectToTest = $this->getService($serviceToTest);
        }catch (ServiceNotFoundException $exception) {
            $serviceToTest = str_replace('Test', '', $reflectionClass->getName());
            $this->objectToTest = new $serviceToTest();
        }
    }

    /**
     * @param string $serviceName
     * @return AbstractService $service
     */
    public function getService(string $serviceName) : AbstractService
    {
        return Rapaxa::getServiceManager()->get($serviceName);
    }

    /**
     * @param string $methodName
     * @param array $methodParameters
     * @return mixed $methodReturn
     */
    public function invokeMethod($methodName, array $methodParameters = [])
    {
        if (is_null($this->objectReflectionClass)) {
            $this->objectReflectionClass = new \ReflectionClass($this->objectToTest);
        }

        $method = $this->objectReflectionClass->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->objectToTest, $methodParameters);
    }

}
