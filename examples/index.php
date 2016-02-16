<?php

include('init.inc.php');

$client = new \SkyVet\Client(CLIENT_ID, CLIENT_SECRET, API_KEY);

$turnos = $client->fetchTurnos();
var_dump($turnos);
