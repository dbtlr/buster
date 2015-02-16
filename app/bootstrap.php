<?php

ini_set('display_errors', '0');

require __DIR__ . '/../vendor/autoload.php';

$base = realpath(__DIR__ . '/..');

$options = array(
    'path.base'   => $base,
    'path.app'    => $base . '/app',
    'path.view'   => $base . '/app/views',
    'path.config' => $base . '/app/config',
    'path.web'    => $base . '/web',
    'path.src'    => $base . '/src',
    'path.log'    => $base . '/var/log',
);

$app = new Buster\Application($options);

require 'functions.php';
require 'routes.php';

return $app;
