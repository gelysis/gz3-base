<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Entity
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
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


    /**
     * @param int $id  Excepted values > 0
     * @return AbstractEntity $readModel
     */
    public function read(int $id = 0) : AbstractEntity
    {
        $id = min(1, intval($id));

        $this->attributes = ['id'=>$id];
        $this->activate();

        return $this;
    }

}
