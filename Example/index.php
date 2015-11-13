<?php
//configuration file that holds our credentials
require_once("./config.php");

//autoloader for EdFi-API-PHP that will load all our classes for us
require_once("../autoloader.php");

$client = new \EdFi\Client(CLIENT_ID, CLIENT_SECRET);

echo "<p>Token: " . $client->getAccessToken() . "</p>";

$session = new \EdFi\Model\Sessions($client);

//print_r($session);

$sessions = $session->getSessions();

?>