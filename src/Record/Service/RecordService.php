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

    /** @var string self::$threadIdentifier */
    protected static $threadIdentifier = null;
    /** @var AbstractActionController self::$controller */

    /** @static EMERG = 0; // System is unusable
     * @static ALERT = 1; // Action must be taken immediately
     * @static CRIT = 2; // Critical conditions
     * @static ERR = 3; // Error conditions */
    const ERROR = self::ERR;
    /** @static WARN = 4; // Warning conditions
     * @static NOTICE = 5; // Normal, but significant, condition
     * @static INFO = 6; // Informational message
     * @static DEBUG = 7; // Debug-level message */
    const DETAIL = 8; // More detailed debug-level message
    const DEVEL = 9; // Development message

    const INVALID_PRIORITY_LABEL = 'INVD';

    /** @var int[] self::$errorPriorityMap */
    /** @var bool|false self::$registeredErrorHandler */    /**
    /** @var bool|false self::$registeredFatalErrorShutdownFunction */
    /** @var bool|false self::$registeredExceptionHandler */
    /** @var string[] $this->priorities */
    /** @var string[] $this->gz3Priorities */
    protected $gz3Priorities = [
        self::ERR=>'ERROR',
        self::DETAIL=>'DETAIL',
        self::DEVEL=>'DEVEL'
    ];
    /** @var SplPriorityQueue $this->writers */
    /** @var WriterPluginManager $this->writerPlugins */
    /** @var SplPriorityQueue $this->processors */
    /** @var ProcessorPluginManager $this->processorPlugins */


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
        }else{
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
