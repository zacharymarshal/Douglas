<?php

error_reporting(E_ALL | E_STRICT);

require dirname(__DIR__) . '/vendor/autoload.php';

function getJasperUrl()
{
    $config = include 'config.php';

    return $config['jasper_url'];
}
