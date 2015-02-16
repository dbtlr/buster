<?php
/**
 * @var Buster\Application $app
 */

$app->get('/', controller('home:pixelate'));
$app->get('/pixel', controller('tracking:pixel'));
$app->get('/pixel/raw', controller('tracking:pixelRaw'));
