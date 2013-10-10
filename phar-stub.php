<?php

Phar::mapPhar();

$base = 'phar://' . __FILE__ . '/';
require_once $base . 'Symfony/Component/ClassLoader/UniversalClassLoader.php';

$classLoader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespaces(array(
    'Douglas' => $base,
));
$classLoader->register();

__HALT_COMPILER();