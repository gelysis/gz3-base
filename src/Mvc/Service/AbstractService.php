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
use Gz3Base\Record\RecordableInterface;
use Gz3Base\Record\RecordableTrait;
use Gz3Base\Record\Service\RecordService;


abstract class AbstractService implements ServiceInterface, RecordableInterface
{
    use ServiceTrait, RecordableTrait;


    /** @var AbstractActionController self::$controller */
    /** @var string[] self::$routeParameters */

    /** @var string[] $this->methodPrefixes */
    /** @var string|null $this->recordIdPrefix */


    /**
     * @return RecordService self::$controller->getRecordService()
     */
    protected function getRecordService()
    {
        return self::$controller->getRecordService();
    }

    /**
     * @return bool $useInitialiseRecording
     */
    protected function useInitialiseRecording()
    {
        return false;
    }

    /**
     * @return bool $useDeinitialiseRecording
     */
    protected function useDeinitialiseRecording()
    {
        return false;
    }

}
