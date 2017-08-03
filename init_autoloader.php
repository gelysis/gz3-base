<?php
/**
 * Zend Framework (http://framework.zend.com/)
 * @link http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @author Zend Technologies USA Inc.
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

$zf3AutoloaderFactory = 'Zend\Loader\AutoloaderFactory';

// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}

if (!class_exists($zf3AutoloaderFactory)) {
    $zf3Path = (file_exists('vendor/zendframework/') ? 'vendor/zendframework/' : null);
    $zf3StandardAutoloader = $zf3Path.'/zend-loader/src/AutoloaderFactory.php';
}

if (isset($zf3Path) && isset($loader)) {
    $loader->add('Zend', $zf3Path.'zend-loader');
    $loader->add('ZendXml', $zf3Path.'zendxml');
}elseif (isset($zf3StandardAutoloader) && file_exists($zf3StandardAutoloader)) {
    include $zf3StandardAutoloader;
    Zend\Loader\AutoloaderFactory::factory(['Zend\Loader\StandardAutoloader'=>['autoregister_zf'=>true]]);
}

if (!class_exists($zf3AutoloaderFactory)) {
    throw new RuntimeException('Unable to load ZF3. Run `php composer.phar install`.');
}
