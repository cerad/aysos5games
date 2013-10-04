<?php

// Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * @var $loader ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('Cerad',   __DIR__  . '/../../cerad2/src');

// AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
