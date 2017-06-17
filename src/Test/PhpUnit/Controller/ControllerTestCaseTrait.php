<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Controller
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

declare(strict_types = 1);
namespace Gz3Base\Test\Controller;


trait ControllerTestCaseTrait
{

    /**
     * Adds the standard application configiuration for the test
     */
    public function getApplicationTestConfiguration()
    {
        $root = strstr(__DIR__, 'vendor/', true);
        $applicationConfigFile = include $root.'config/application.config.php';

        return $applicationConfigFile;
    }

    /**
     * Reset the application and re-add the standard application configiuration
     */
    public function setUp()
    {
        parent::setUp();
        $this->setApplicationConfig($this->getApplicationTestConfiguration());
    }

}
