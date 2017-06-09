<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Record\Filter;

use Gz3Base\Mvc\Exception\InvalidArgumentException;
use Gz3Base\Record\Service\RecordService;
use Traversable;
use Zend\Log\Filter\FilterInterface;


class Priorities implements FilterInterface
{
    /** @var int[] $this->priorities */
    protected $priorities;
    /** @var string $this->operator */
    protected $operator;

    /**
     * Filter recording only certain priorities.
     * @todo: check if it works with a strict type hinting to array
     * @param  int[]|Traversable $priorities
     * @param  string $operator Comparison operator
     * @return void
     * @throws InvalidArgumentException
     */
    public function __construct($priorities, string $operator = '')
    {
        if ($priorityies instanceof Traversable) {
            $priorities = iterator_to_array($priorities);
        }

        if (is_array($priorities) && isset($priorities['priorities'])) {
            $priorities = $priorities['priorities'];
        }

        if (is_array($priorities) && count($priorities) > 0) {
            $valid = $this->arePrioriesValid($priorities);
            $valid &= $this->isOperatorValid($operator);

            if (!$valid) {
                throw new InvalidArgumentException(sprintf(
                    'Priorities filter data is not valid: $priorties = %s, $operator = %s.',
                    var_export($priorities, true), var_export($operator, true)
                ));
            }
        }else{
            throw new InvalidArgumentException(sprintf(
                'Filter constructor did not receive valid priorties data but %s.',
                var_export($priorities, true)
            ));
        }
    }

    /**
     * @param int[] $priorities
     * @throws Exception\InvalidArgumentException
     * @return bool $arePrioritiesValid
     */
    protected function arePrioritiesValid(array $priorities) : bool
    {
        $arePrioritiesValid = true;
        foreach ($priorities as $priority) {
            $arePrioritiesValid &= RecordService::isValidRecordPriority($priority);
        }

        if ($arePrioritiesValid) {
            $this->priorities = $priorities;
        }else{
            $this->priorities = null;
            throw new InvalidArgumentException(sprintf(
                'Filter priorities have to be an array of valid numbers, received %s.',
                var_export($priorities, true)
            ));
        }

        return (bool) $arePrioritiesValid;
    }

    /**
     * @param string $operator
     * @throws Exception\InvalidArgumentException
     * @return bool $isOperatorValid
     */
    protected function isOperatorValid(string $operator) : bool
    {
        switch ($operator) {
          case '':
          case '=':
          case '==':
          case 'eq':
            $isOperatorValid = true;
            $this->operator = 'eq';
            break;
          case '!=':
          case '!==':
          case '<>':
          case 'neq':
          case 'ne':
            $isOperatorValid = true;
            $this->operator = 'ne';
            break;
          default:
            $isOperatorValid = false;
            $this->operator = '';
            throw new InvalidArgumentException(sprintf(
                'Record priorities operator must be either ==, =, eq, !==, neq, !=, <> or ne, received "%s".',
                $operator
            ));
        }

        return $isOperatorValid;
    }

    /**
     * {@inheritDoc}
     * @see \Zend\Log\Filter\FilterInterface::filter()
     */
    public function filter(array $event)
    {
        $isPriorityInPriorityArray = in_array($event['priority'], $this->priorities);

        return ($isPriorityInPriorityArray xor $this->operator == 'neq');
    }

}
