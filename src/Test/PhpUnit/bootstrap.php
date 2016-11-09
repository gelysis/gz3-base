<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * Generic phpunit bootstrap file
 * @package Gz3Base
 * @author Andreas Gerhards <geolysis@zoho.com>
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause -
 * @copyright ©2016, Andreas Gerhards <geolysis@zoho.com>
 */

require_once __DIR__.'/TestInitialiser.php';

error_reporting(E_ALL | E_STRICT);

chdir(TestInitialiser::getRootPath());
include 'init_autoloader.php';

TestInitialiser::init();
