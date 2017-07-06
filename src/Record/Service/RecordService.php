<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Service
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Record\Service;

use Gz3Base\Mvc\Controller\AbstractActionController;
use Gz3Base\Mvc\Service\ServiceInterface;
use Gz3Base\Mvc\Service\ServiceTrait;
use Zend\Log\Logger;


class RecordService extends Logger implements ServiceInterface
{
    use ServiceTrait;

    /** @var self::EMERG  System is unusable */
    /** @var self::ALERT  Action must be taken immediately */
    /** @var self::CRIT  Critical conditions */
    /** @var self::ERROR = self::ERR  Error conditions */
    const ERROR = self::ERR;
    /** @var self::WARN  Warning conditions */
    /** @var self::NOTICE  Normal, but significant condition */
    /** @var self::INFO  Informational messages */
    /** @var self::DEBUG  Debug messages */
    /** @var self::DETAIL  More detailed debug messages */
    const DETAIL = 8;
    /** @var self::DEVEL  Development message */
    const DEVEL = 9;
    /** @var self::INVALID_PRIORITY_LABEL */
    const INVALID_PRIORITY_LABEL = 'INVD';

    /** @var string self::$threadIdentifier */
    protected static $threadIdentifier = null;
    /** @var AbstractActionController self::$controller */
    /** @var int[] self::$errorPriorityMap */
    /** @var bool|false self::$registeredErrorHandler */
    /** @var bool|false self::$registeredFatalErrorShutdownFunction
    /** @var bool|false self::$registeredExceptionHandler */

    /** @var string[] $this->priorities */
    /** @var string[] $this->gz3Priorities */
    protected $gz3Priorities = [
        self::ERROR=>'ERROR',
        self::DETAIL=>'DETAIL',
        self::DEVEL=>'DEVEL'
    ];
    /** @var SplPriorityQueue $this->writers */
    /** @var WriterPluginManager $this->writerPlugins */
    /** @var SplPriorityQueue $this->processors */
    /** @var ProcessorPluginManager $this->processorPlugins */
    /** @var string[] $this->methodPrefixes */


    /**
     * Accepted option keys:
     *   writers: array of writers to add to this record service (keys: name [, priority][, options])
     *   processors: array of processors to add to this record service (keys: name [, priority][, options])
     *   exceptionhandler: if true register this record service as exceptionhandler
     *   errorhandler: if true register this record service as errorhandler
     *   fatal_error_shutdownfunction: if true register this record service as fatal error shutdown
     * @param array|Traversable|null $options
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->priorities = array_replace($this->priorities, $this->gz3Priorities);
    }

    /**
     * Set self::$threadIdentifier
     * @param string $threadIdentifier
     * @return RecordService $this
     */
    public function setThreadIdentifier(string $threadIdentifier) : RecordService
    {
        if (is_null(self::$threadIdentifier)) {
            self::$threadIdentifier = $threadIdentifier;
        }

        return $this;
    }

    /**
     * @param int $priority
     * @return bool $isValidPriority
     */
    public static function isValidPriority(int $priority) : bool
    {
        return in_array($priority, array_keys(self::$priorities));
    }

    /**
     * @param int $priority
     * @return bool $isValidPriorityLabel
     */
    public static function isValidPriorityLabel(string $priority) : bool
    {
        return in_array($priority, self::$priorities);
    }

    /**
     * @param string $id
     * @param int $priority
     * @param string $message
     * @return string $logMessage
     */
    protected function getLogMessage(string $id, int $priority, string $message) : string
    {
        if (self::isValidPriority($priority)) {
            $priorityLabel = self::$priorities[$priority];
        }else {
            $priorityLabel = self::INVALID_PRIORITY_LABEL;
        }
        $logMessage = '['.str_pad($priorityLabel.':'.$id.']', 24).' '.$message;

        return $logMessage;
    }

    /**
     * @param int $id
     * @param string $priority
     * @param string $message
     * @param array $data
     * @return bool $success
     */
    public function record(string $id, int $priority, string $message, array $data = []) : bool
    {
        $logMessage = $this->getLogMessage($id, $priority, $message);
        $this->log($priority, $logMessage, $data);

        return true;
    }

}
