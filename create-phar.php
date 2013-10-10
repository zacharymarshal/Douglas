<?php

$phar = new Phar('build/douglas.phar', 0, 'douglas.phar');
$phar->buildFromDirectory('vendor/symfony/class-loader');
$phar->buildFromDirectory('src');
$phar->setStub(file_get_contents('phar-stub.php'));