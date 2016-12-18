<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Entity
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright Copyright ©2016 Andreas Gerhards
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Mvc\Entity;

use Gz3Base\Mvc\Manager\AbstractManager;


class NoopEntity extends AbstractEntity
{
    /** @var AbstractActionController self::$controller */
    /** @var string self::$routeParameters */

    /** @var AbstractManager $this->manager */
    /** @var array $this->attributes */
}
