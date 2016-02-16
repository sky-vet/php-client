<?php

include 'init.inc.php';

$client = new \SkyVet\Client(CLIENT_ID, CLIENT_SECRET, API_KEY);

$turno = new \SkyVet\Model\Turno(array(
    'nombre' => 'Juan Pérez',
    'telefono' => '5555-5555',
    'date' => \DateTime::createFromFormat('d/m/Y H:i', '26/02/2016 09:00'),
    'comentarios' => 'Comentarios de prueba'
));

$client->saveTurno($turno);

var_dump($turno);