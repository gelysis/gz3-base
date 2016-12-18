<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Exception
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright Copyright ©2016 Andreas Gerhards
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Exception;

use Gz3Base\Record\RecordableInterface;
use Gz3Base\Record\RecordableTrait;
use Zend\Mvc\Exception\InvalidArgumentException as ZendInvalidArgumentException;


class InvalidArgumentException extends ZendInvalidArgumentException implements RecordableInterface
{
    use RecordableTrait;

    /**
     * {inheritDoc}
     * @see \Gz3Base\Record\RecordableTrait::useInitialiseRecording()
     */
    protected function useInitialiseRecording()
    {
        return false;
    }

    /**
     * {inheritDoc}
     * @see \Gz3Base\Record\RecordableTrait::useDeinitialiseRecording()
     */
    protected function useDeinitialiseRecording()
    {
        return false;
    }

}
