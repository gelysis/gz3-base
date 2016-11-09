<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Service
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards <geolysis@zoho.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
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

    /*    EMERG = 0;  // System is unusable
     *    ALERT = 1;  // Action must be taken immediately
     *    CRIT = 2;  // Critical conditions
     *    ERR = 3;  // Error conditions */
    const ERROR = self::ERR;
    /*    WARN = 4;  // Warning conditions
     *    NOTICE = 5;  // Normal but significant condition
     *    INFO = 6;  // Informational message
     *    DEBUG = 7;  // Debug-level message */
    const DETAIL = 8;  // More detailed debug-level message
    const DEVEL = 9;  // Development message

    const INVALID_PRIORITY_LABEL = 'INVD';

    /** @var string self::$threadIdentifier */
    protected static $threadIdentifier = null;
    /** @var AbstractActionController self::$controller */

    /** @var int[] self::$errorPriorityMap
     *  @var bool|false self::$registeredErrorHandler
     *  @var bool|false self::$registeredFatalErrorShutdownFunction
     *  @var bool|false self::$registeredExceptionHandler
     *  @var string[] $this->priorities
     *  @var string[] $this->gz3Priorities */
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
    public function isValidPriority(int $priority) : bool
    {
        return in_array($priority, array_keys($this->priorities));
    }

    /**
     * @param int $priority
     * @return bool $isValidPriorityLabel
     */
    public function isValidPriorityLabel(string $priority) : bool
    {
        return in_array($priority, $this->priorities);
    }

    /**
     * @param string $id
     * @param int $priority
     * @param string $message
     * @return string $logMessage
     */
    protected function getLogMessage(string $id, int $priority, string $message) : string
    {
        if ($this->isValidPriority($priority)) {
            $priorityLabel = $this->priorities[$priority];
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
