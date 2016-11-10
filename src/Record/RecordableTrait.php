<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * Recordable Trait
 * @package Gz3Base\Record
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Record;

use Gz3Base\Record\Service\RecordService;


trait RecordableTrait
{

    /** @var \ReflectionClass|null $this->reflectionClass */
    protected $reflectionClass = null;
    /** @var string[] $this->methodNames */
    protected $methodNames = [];
    /** @var int[] $this->methodStart */
    protected $methodStart = [];
    /** @var string|null $this->recordIdPrefix */
    protected $recordIdPrefix = null;


    /**
     * @return \ReflectionClass $this->reflectionClass
     */
    protected function getReflectionClass() : \ReflectionClass
    {
        if (is_null($this->reflectionClass)) {
            $this->reflectionClass = new \ReflectionClass($this);
        }

        return $this->reflectionClass;
    }

    /**
     * @return string $fullClassname
     */
    public function getFullClassname() : string
    {
        return $this->getReflectionClass()->getName();
    }

    /**
     * @return string $namespace
     */
    public function getNamespace() : string
    {
        return $this->getReflectionClass()->getNamespaceName();
    }

    /**
     * @return string $classname
     */
    public function getShortClassname() : string
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
            $this->recordIdPrefix = '';

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
     * @param string $methodName
     * @return string $recordId
     */
    protected function getAbbreviatedMethodName(string $methodName = '') : string
    {
        if ($methodName == '' && count($this->methodNames) > 0) {
            $methodNames = $this->methodNames;
            $methodName = array_pop($methodNames);
        }
        $recordId = strtolower(substr($methodName, 0, 3));

        return $recordId;
    }

    /**
     * setSecondsPrecision()
     * @param float $seconds
     * @return float $seconds
     */
    protected function setSecondsPrecision(float $seconds) : float
    {
        if ($seconds < 10) {
            $decimals = min(3, 1 - floor(log($seconds, 10)));
            $seconds = ceil($seconds * pow(10, $decimals)) / pow(10, $decimals);
        }elseif ($seconds < 30) {
            $seconds = ceil($seconds * 2) / 2;
        }else{
            $seconds = ceil($seconds);
        }

        return $seconds;
    }

    /**
     * @param float $seconds
     * @return string $formattedInterval
     */
    protected function formatSeconds(float $seconds) : string
    {
        $times = ['h'=>floor($seconds / 3600)];
        $times['min'] = floor(($seconds - $times['h'] * 3600) / 60);
        $times['s'] = $this->setSecondsPrecision($seconds) - $times['h'] * 3600 - $times['min'] * 60;

        $formattedTimes = [];
        foreach ($times as $unit=>&$value) {
            if ($value != 0) {
                $formattedTimes[] = $value.$unit;
            }
        }

        if (count($formattedTimes) == 0) {
            $formattedInterval = '0.000s';
        }else{
            $formattedInterval = implode(', ', $formattedTimes);
        }

        return $formattedInterval;
    }

    /**
     * @return bool $useInitialiseRecording
     */
    abstract protected function useInitialiseRecording() : bool;

    /**
     * @param string $methodName
     * @return string $recordId
     */
    protected function initialiseMethod(string $methodName) : string
    {
        $this->methodStart[] = microtime(true);
        $this->methodNames[] = $methodName;

        $recordId = $this->getAbbreviatedMethodName($methodName).'_sta';
        $message = $methodName.' initialised at '.strftime('%H:%M:%S').' on '.strftime('%d.%m.%Y');
        $data = ['init'=>current($this->methodStart)];

        if ($this->useInitialiseRecording()) {
            $this->record($recordId, RecordService::DEVEL, $message, $data);
        }

        return $recordId;
    }

    /**
     * @return bool $useDeinitialiseRecording
     */
    abstract protected function useDeinitialiseRecording() : bool;

    /**
     * @param string $methodName
     * @param bool|false $forced
     * @return string $recordId
     */
    protected function deinitialiseMethod(string $methodName, bool $forced = false) : string
    {
        $end = microtime(true);

        if (!count($this->methodNames) || !count($this->methodStart)) {
            $runtime = 0;
            $recordId = '_noi';
            $priority = RecordService::WARN;
            $message = '. Could not find init information.';
            $data = [
                'method name'=>current($this->methodNames),
                'method start'=>current($this->methodStart)
            ];
        }else{
            $start = array_pop($this->methodStart);
            $name = array_pop($this->methodNames);

            $runtime = $this->setSecondsPrecision($end - $start);

            if ($methodName != $name) {
                $recordId = '_err';
                $priority = RecordService::WARN;
                $message = '. Could only find init information of '.$name.'.';
            }else{
                $recordId = '_end';
                $priority = RecordService::INFO;
                $message = '. Runtime was '.$this->formatSeconds($runtime).'.';
            }

            $data = ['runtime'=>$runtime];
        }

        if ($forced) {
            $methodName = 'Forced '.$methodName.' to be';
            $recordId = 'f'.$recordId;
        }

        $message = $methodName.' deinitialised at '.strftime('%H:%M:%S').' on '.strftime('%d.%m.%Y').$message;
        $data['deinit'] = $end;

        if ($this->useDeinitialiseRecording()) {
            $this->record($this->getAbbreviatedMethodName($methodName).$recordId, $priority, $message, $data);
        }

        return $recordId;
    }

}
