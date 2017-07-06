<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Record;

use Gz3Base\Mvc\Service\ServiceTrait;
use Gz3Base\Record\Service\RecordService;
use Gz3Base;


trait RecordableTrait
{

    /** @var bool self::INIT_RECORDING : Essential implementation */
    /** @var bool self::DEINIT_RECORDING : Essential implementation */

    /** @var \ReflectionClass|null $this->reflectionClass */
    protected $reflectionClass = null;
    /** @var string[] $this->methodName */
    protected $methodName = [];
    /** @var int[] $this->methodStart */
    protected $methodStart = [];
    /** @var string|null $this->recordIdPrefix */
    protected $recordIdPrefix = null;


    /**
     * @return \ReflectionClass $this->reflectionClass
     */
    protected function getReflectionClass()
    {
        if (is_null($this->reflectionClass)) {
            $this->reflectionClass = new \ReflectionClass($this);
        }

        return $this->reflectionClass;
    }

    /**
     * @deprecated  Will be removed in version 1.1.0 latest
     * @return mixed $this
     */
    public function setReflectionClass()
    {
        trigger_error(sprintf(
                '%s is deprecated as of 0.9.5 and will be removed in future versions. Please use %s.',
                __METHOD__.'()',
                __CLASS__.'::getReflectionClass()'
            ),
            E_USER_DEPRECATED
        );

        if (is_null($this->reflectionClass)) {
            $this->reflectionClass = new ReflectionClass($this);
        }

        return $this;
    }

    /**
     * @return string $fullClassname
     */
    protected function getFullClassname() : string
    {
        return $this->getReflectionClass()->getName();
    }

    /**
     * @return string $classname
     */
    protected function getShortClassname() : string
    {
        return $this->getReflectionClass()->getShortName();
    }

    /**
     * [@param string $classname]
     * @return string $abbreviatedClassname
     */
    protected function getAbbreviatedClassname(string $classname = '') : string
    {
        if (strlen($classname) == 0) {
            $classname = $this->getReflectionClass()->getShortName();
        }

        return strtolower(substr($classname, 0, 2));
    }

    /**
     * @return string $this->recordIdPrefix
     */
    protected function getRecordIdPrefix() : string
    {
        if (is_null($this->recordIdPrefix)) {
            $namespaceArray = explode('\\', $this->getReflectionClass()->getNamespaceName(), 4);
            foreach ($namespaceArray as $key=>$part) {
                $length = (int) max(1, ceil((4 - $key - count($namespaceArray)) / 2) * 2);
                $this->recordIdPrefix .= strtolower(substr($part, 0, $length));
            }

            $this->recordIdPrefix .= '_'.$this->getAbbreviatedClassname().'_';
        }

        return $this->recordIdPrefix;
    }

    /**
     * @see RecordService::record()
     * @param int $id
     * @param string $priority
     * @param string $message
     * [@param array $data]
     * @return bool $success
     */
    public function record(string $id, int $priority, string $message, array $data = []) : bool
    {
        $recordId = $this->getRecordIdPrefix().$id;
        return $this->geRecordService()->record($recordId, $priority, $message, $data);
    }

    /**
     * @todo  Check if there is a better way to implement this check
     * @return bool $hasPrefix
     */
    protected function hasPrefix(string $method) : bool
    {

        $hasPrefix = in_array(strtolower(substr($method, 0, 3)), $methodPrefixes);

        return $hasPrefix;
    }

    /**
     * @param string $methodName
     * @return string $recordId
     */
    protected function getAbbreviatedMethodName(string $methodName = '') : string
    {
        if ($methodName == '' && $this->methodName) {
            $methodName = $this->methodName;
            $methodName = array_pop($methodName);
        }

        if (method_exists($this, 'getMethodPrefixes')) {
            $methodPrefixes = $this->getMethodPrefixes();
        }else {
            $methodPrefixes = ServiceTrait::getDefaultMethodPrefixes();
        }

        $prefix = '';
        if (is_array($methodPrefixes)) {
            foreach ($methodPrefixes as $methodPrefix) {
                if (strtolower(substr($method, 0, strlen($prefix))) == $prefix) {
                    $prefix = $methodPrefix;
                    break;
                }
            }
        }

        if (strlen($prefix) > 0) {
            $method = substr($method, strlen($prefix));
            $recordId = substr($methodName, 0, 1).substr(
                strlen(preg_replace('#[a-z]#', '', $method)) > 1 ? preg_replace('#[a-z]#', '', $method) : $method,
                0, 2
            );
        }else {
            $recordId = strtolower(substr($methodName, 0, 3));
        }

        return $recordId;
    }

    /**
     * setSecondsPrecision()
     * @param double $seconds
     * @return double $seconds
     */
    protected function setSecondsPrecision(double $seconds) : double
    {
        if ($seconds < 10) {
            $decimals = min(3, 1 - floor(log($seconds, 10)));
            $seconds = ceil($seconds * pow(10, $decimals)) / pow(10, $decimals);
        }elseif ($seconds < 30) {
            $seconds = ceil($seconds * 2) / 2;
        }else {
            $seconds = ceil($seconds);
        }

        return $seconds;
    }

    /**
     * @param double $seconds
     * @return string $formattedInterval
     */
    protected function formattedSeconds(double $seconds) : string
    {
        $times = [];
        $times['h'] = floor($seconds / 3600);
        $times['min'] = floor(($seconds - $times['h'] * 3600) / 60);
        $times['s'] = $this->setSecondsPrecision($seconds) - $times['h'] * 3600 - $times['min'] * 60;

        $formattedTimes = [];
        foreach ($times as $unit=>&$value) {
            if ($value != 0) {
                $formattedTimes[] = $value.$unit;
            }
        }
        unset($times);

        if (count($formattedTimes) == 0) {
            $formattedSeconds = '0.000s';
        }else {
            $formattedSeconds = implode(', ', $formattedTimes);
        }
        unset($formattedTimes);

        return $formattedSeconds;
    }

    /**
     * @return bool $useInitialiseRecording
     */
    protected function useInitialiseRecording() : bool
    {
        return static::INIT_RECORDING;
    }

    /**
     * @param string $methodName
     * @return string $recordId
     */
    protected function initialiseMethod(string $methodName) : string
    {
        $this->methodStart[] = microtime(true);
        $this->methodName[] = $methodName;

        $recordId = $this->getAbbreviatedMethodName($methodName).'_sta';
        $message = $methodName.' initialised at '.strftime('%H:%M:%S').' on '.strftime('%d.%m.%Y');
        $data = [
            'init'=>current($this->methodStart)
        ];

        if ($this->useInitialiseRecording()) {
            $this->record($recordId, RecordService::DEVEL, $message, $data);
        }

        return $recordId;
    }

    /**
     * @return bool $useDeinitialiseRecording
     */
    protected function useDeinitialiseRecording() : bool
    {
        return static::DEINIT_RECORDING;
    }

    /**
     * @param string $methodName
     * @param bool|false $forced
     * @return string $recordId
     */
    protected function deinitialiseMethod(string $methodName, bool $forced = false) : string
    {
        $end = microtime(true);

        if (! count($this->methodName) || ! count($this->methodStart)) {
            $runtime = 0;
            $recordId = $this->getAbbreviatedMethodName($methodName).'_noi';
            $priority = RecordService::WARN;
            $message = '. Could not find init information.';
            $data = [
                'method name'=>current($this->methodName),
                'method start'=>current($this->methodStart)
            ];
        }else {
            $start = array_pop($this->methodStart);
            $name = array_pop($this->methodName);

            $runtime = $this->setSecondsPrecision($end - $start);

            if ($methodName != $name) {
                $recordId = $this->getAbbreviatedMethodName($methodName).'_ier';
                $priority = RecordService::WARN;
                $message = '. Could only find init information of '.$name.'.';
            }else {
                $recordId = $this->getAbbreviatedMethodName($methodName).'_end';
                $priority = RecordService::INFO;
                $message = '. Runtime was '.$this->formattedSeconds($runtime).'.';
            }
            $data = [
                'runtime'=>$runtime
            ];
        }

        if ($forced) {
            $methodName = 'Forced '.$methodName.' to be';
            $recordId = $this->getAbbreviatedMethodName($methodName).'f';
        }

        $message = $methodName.' deinitialised at '.strftime('%H:%M:%S').' on '.strftime('%d.%m.%Y').$message;
        $data['deinit'] = $end;

        if ($this->useDeinitialiseRecording()) {
            $this->record($recordId, $priority, $message, $data);
        }

        return $recordId;
    }

}
