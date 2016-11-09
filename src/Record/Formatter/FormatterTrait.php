<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards <geolysis@zoho.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Record\Formatter;


trait FormatterTrait
{

    /** @var $this->messageLimit */
    protected $messageLimit;

    /**
     * @param int $messageLimit
     */
    public function setMessageLimit(int $messageLimit)
    {
        $this->messageLimit = $messageLimit;
    }

}
