<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards <geolysis@zoho.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

namespace Gz3Base\Test\PhpUnit;

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
        return TestInitialiser::getServiceManager()->get($serviceName);
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
