<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

namespace Gz3Base\Test\PhpUnit\Model;

use Gz3Base\Mvc\Exception\Gz3Exception;
use Gz3Base\Mvc\Service\AbstractService;
use Gz3Base\Test\PhpUnit\TestInitialiser;
use Zend\ServiceManager\ServiceManager;


class Gz3TestCase extends \PHPUnit_Framework_TestCase
{

    /** @var bool self::ALLOW_MANUAL_SET_OF_INVOKE_METHOD_CLASS */
    protected const ALLOW_MANUAL_SET_OF_INVOKE_METHOD_CLASS = false;

    /** @var ServiceManager $this->serviceManager */
    protected $serviceManager = null;
    /** @var mixed $this->defaultInvokeMethodClass */
    protected $objectToTest = null;
    /** @var mixed $this->invokeMethodClass */
    protected $invokeMethodClass = null;


    /**
     * setUp method expecting the following naming convention: Ns1\..\Classname and Ns1Test\..\ClassnameTest
     * {@inheritdoc}
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $reflectionClass = new \ReflectionClass($this);
        try {
            $serviceToTest = strtolower(strstr($reflectionClass->getShortName(), 'Test', true));
            $this->objectToTest = $this->getService($serviceToTest);
        }catch (\Exception $exception) {
            $objectToTest = str_replace('Test', '', $reflectionClass->getName());
            if (class_exists($objectToTest)) {
                try {
                    $this->objectToTest = new $objectToTest();
                }catch (\Exception $exception) {
                    unset($this->objectToTest);
                }
            }else {
                unset($this->objectToTest);
            }
            if (isset($this->objectToTest) && $this->isStrict()) {
                $exceptionMessage = '$this->objectToTest could not be set on '.$reflectionClass->getName()
                    .' ('.$exception->getMessage().' ). Please follow naming convention.';
                throw new Gz3Exception($exceptionMessage, $exception->getCode());
            }
        }
    }

    /**
     * @return ServiceManager $this->serviceManager
     */
    protected function getServiceManager() : ServiceManager
    {
        if (is_null($this->serviceManager)) {
            $this->serviceManager = TestInitialiser::getServiceManager()->get($serviceName);
        }

        return $this->serviceManager;
    }

    /**
     * @param string $serviceName
     * @return AbstractService $service
     */
    protected function getService(string $serviceName) : AbstractService
    {
        return $this->getServiceManager()->get($serviceName);
    }

    /**
     * @return bool $isManualSetOfInvokeMethodClassAllowed
     */
    protected function isStrict()
    {
        return !static::ALLOW_MANUAL_SET_OF_INVOKE_METHOD_CLASS;
    }

    /**
     * @return \Gz3Base\Test\PhpUnit\Model\TestCase $this
     */
    protected function setObjectTest()
    {
        $reflectionClass = new \ReflectionClass($this);
        try {
            $serviceToTest = strtolower(strstr($reflectionClass->getShortName(), 'Test', true));
            $this->objectToTest = $this->getService($serviceToTest);
        }catch (\Exception $exception) {
            $objectToTest = str_replace('Test', '', $reflectionClass->getName());
            try {
                $this->objectToTest = new $objectToTest();
            }catch (\Exception $exception) {
            }
        }

        return $this;
    }

    /**
     * @param object $class
     * @param object $property
     * @throws Gz3Exception
     * @return \Gz3Base\Test\PhpUnit\Model\TestCase
     */
    private function setClassOnProperty($class, $property)
    {
        if ($this->isStrict()) {
            $backtrace = debug_backtrace();
            throw new Gz3Exception('Usage of '.($backtrace[0]['function'] ?? __FUNCTION__).'() is not allowed.');
        }else {
            $this->$property = $class;
        }

        return $this;
    }

    /**
     * @param object $invokeMethodClass
     */
    protected function setInvokeMethodClass($invokeMethodClass)
    {
        return $this->setClassOnProperty($invokeMethodClass, 'invokeMethodClass');
    }

    /**
     * @param object $invokeMethodClass
     */
    protected function setInvokeMethodClassPermanently($invokeMethodClass)
    {
        $this->setClassOnProperty($invokeMethodClass, 'objectToTest');
        $this->invokeMethodClass = null;

        return $this;
    }

    /**
     * @return \ReflectionClass $invokeMethodClass
     */
    protected function getInvokeMethodReflectionClass()
    {
        if (!is_null($this->invokeMethodClass)) {
            $invokeMethodReflectionClass = $this->invokeMethodClass;

        }elseif (!is_null($this->objectToTest())) {
            $invokeMethodReflectionClass= $this->objectToTest;
        }

        return new \ReflectionClass($invokeMethodClass);
    }

    /**
     * @param string $methodName
     * @param array $methodParameters
     * @return mixed $methodReturn
     */
    public function invokeMethod(string $methodName, array $methodParameters = [])
    {
        $objectReflectionClass = $this->getInvokeMethodReflectionClass();
        $method = $objectReflectionClass->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->objectToTest, $methodParameters);
    }

}
