<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Exception
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Exception;

use Gz3Base\Mvc\Service\ServiceInterface;
use Zend\Mvc\Exception\ExceptionInterface;


trait ExceptionTrait
{

    /** @var \Zend\Mvc\Exception\ExceptionInterface $this->recordService */
    protected $recordService = null;


    /**
     * @param \Gz3Base\Record\Service\RecordService $this->$recordService
     * @return \Zend\Mvc\Exception\ExceptionInterface $this
     */
    public function setRecordService(ServiceInterface $recordService) : ExceptionInterface
    {
        $this->recordService = $recordService;

        return $this;
    }

    /**
     * @return \Gz3Base\Record\Service\RecordService $this->recordService
     */
    public function getRecordService() : ServiceInterface
    {
        return $this->recordService;
    }

}
