<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * Recordable Interface
 * @package Gz3Base\Model
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards <geolysis@zoho.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */
declare(strict_types = 1);
namespace Gz3Base\Record;

use ReflectionClass;

interface RecordableInterface
{

    /** @var ReflectionClass $this->reflectionClass */
    /** @var string $this->recordIdPrefix */
    /** @var array $this->methodName */
    /** @var array $methodStart */

    /**
     * @param string $id
     * @param int $priority
     * @param string $message
     * @param array $data
     * @return bool $success
     */
    public function record(string $id, int $priority, string $message, array $data = array()) : bool;

    /**
     * @return string $fullClassname
     */
    public function getFullClassname() : string;

    /**
     * @return string $classname
     */
    public function getShortClassname() : string;

}