<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Record\Writer;

use Zend\Log\Writer\AbstractWriter;


class RabbitMq extends AbstractWriter
{

    /**
     * {@inheritDoc}
     * @see \Zend\Log\Writer\AbstractWriter::doWrite()
     */
    protected function doWrite(array $event)
    {}

}
