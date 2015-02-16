<?php

$driver   = 'pdo_pgsql';
$host     = 'localhost';
$port     = '5432';
$username = 'buster';
$password = '';
$dbname   = 'buster';

$url = null;
$parsedUrl = array();

// USE HEROKU DATABASE CONFIG
if (isset($_ENV['HEROKU_POSTGRESQL_GREEN_URL'])) {
    $url = $_ENV['HEROKU_POSTGRESQL_GREEN_URL'];
    $parsedUrl = parse_url($url);
}

$config = array(
    'driver'   => $driver,
    'dbname'   => isset($parsedUrl['path']) ? trim($parsedUrl['path'], '/') : $dbname,
    'host'     => isset($parsedUrl['host']) ? $parsedUrl['host'] : $host,
    'port'     => isset($parsedUrl['port']) ? $parsedUrl['port'] : $port,
    'user'     => isset($parsedUrl['user']) ? $parsedUrl['user'] : $username,
    'password' => isset($parsedUrl['pass']) ? $parsedUrl['pass'] : $password,
);

return $config;