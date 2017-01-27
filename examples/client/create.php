<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../init.inc.php';

use SkyVet\Client as ApiClient;
use SkyVet\Model\Client;

$apiClient = new ApiClient(CLIENT_ID, CLIENT_SECRET, API_KEY);

$client = new Client();
$client->setFirstName('John');
$client->setLastName('Doe');
$client->addCustomField(['DNI' => '123456']);
$client->setEmail('john@doe.com');
$client->setPhone('555-5555');
$client->setCity('Gotham City');
$client->setAddress('False Address 123');
$client->setUniqueCustomField('DNI');

$apiClient->saveClient($client);

var_dump($client);
