<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * Generic phpunit bootstrap file
 * @package Gz3Base
 * @package Record
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause -
 * @copyright Copyright ©2016 Andreas Gerhards
 */

declare(strict_types = 1);
error_reporting(E_ALL | E_STRICT);

$root = strstr(__DIR__, 'vendor/', true);
$moduleTestPaths = [];

chdir($root);
$applicationConfig = include 'config/application.config.php';

foreach ($applicationConfig['module_listener_options']['module_paths'] as $modulePath) {
    foreach ($applicationConfig['modules'] as $module) {
        $testPath = realpath($modulePath.'/'.$module.'/test');
        if ($testPath) {
            $moduleTestPaths[$module] = $testPath;
        }
    }
}

require_once __DIR__.'/TestInitialiser.php';
require_once 'config/autoload/rapaxa.php';
include 'init_autoloader.php';

TestInitialiser::init($applicationConfig, $moduleTestPaths);
