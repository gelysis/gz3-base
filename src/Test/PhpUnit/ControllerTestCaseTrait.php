<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\Model
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @copyright ©2016, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please view LICENSE.md for more information
 */

namespace Gz3Base\Test;


trait ControllerTestCaseTrait
{

    /**
     * Adds the standard application configiuration for the test
     * @return array $applicationConfig
     */
    public function getApplicationTestConfiguration()
    {
        $root = strstr(__DIR__, 'vendor/', true);
        $applicationConfigFile = $root.'config/application.config.php';

        if (file_exists($localConfigFile)) {
            $applicationConfig = array_replace_recursive(include $applicationConfigFile, include $localConfigFile);
        }else{
            $applicationConfig = include $applicationConfigFile;
        }

        return $applicationConfig;
    }

    /**
     * Reset the application and re-add the standard application configiuration
     */
    public function setUp()
    {
        parent::setUp();
        $this->setApplicationConfig(
            $this->getApplicationTestConfiguration()
        );
    }

}
