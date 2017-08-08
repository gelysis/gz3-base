<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * Generic phpunit bootstrap file
 * @package Gz3Base
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause -
 */

declare(strict_types = 1);
error_reporting(E_ALL | E_STRICT);

$root = strstr(__FILE__, 'test'.DIRECTORY_SEPARATOR, true);
chdir($root);

include 'init_autoloader.php';
require_once 'src'.DIRECTORY_SEPARATOR.'Test'.DIRECTORY_SEPARATOR.'PhpUnit'.DIRECTORY_SEPARATOR.'TestInitialiser.php';
$applicationConfig = include 'config'.DIRECTORY_SEPARATOR.'application.config.php';

//\Gz3Base\Test\PhpUnit\TestInitialiser::init($applicationConfig);

require_once 'test'.DIRECTORY_SEPARATOR.'Fixture'.DIRECTORY_SEPARATOR.'Module.php';
\Gz3Base\Test\PhpUnit\TestInitialiser::setServiceManager(\Gz3BaseTest\Fixture\Module::getConfig());
