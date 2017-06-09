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

use Gz3Base\Record\Service\RecordService;


class NoopRecordService extends RecordService
{

    /**
     * {@inheritDoc}
     * @see \Zend\Log\Logger::record()
     */
    public function log($priority, $message, $extra = [])
    {
        try {
            $recordService = parent::log($priority, $message, $extra = []);
        }catch (\Zend\Log\Exception\ExceptionInterface $exception) {}

        return $this;
    }

}
