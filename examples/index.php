<?php

include('vendor/autoload.php');

$client_id = '1_2sa6fb8qqwcgwggg4k80k8sco0oc00g0sgkg0cccsgw4c0cgo8';
$client_secret = '56s4s15149wk0kkko8ws84w8w0gco8088sks0c4s8s08488cog';
$api_key = '3c37a35e7ae8fd6fc5c9e374c9a2b5a7';

$client = new \SkyVet\Client($client_id, $client_secret, $api_key);

$turnos = $client->fetchTurnos();
var_dump($turnos);
