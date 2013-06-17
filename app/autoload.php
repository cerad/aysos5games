<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var $loader ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('Cerad',   __DIR__  . '/../../cerad201306v/src');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
