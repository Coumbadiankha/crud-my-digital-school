<?php

use Controller\Controller;

require_once('Autoload.php');

$controller = new Controller;

//echo "<pre>"; var_dump($controller);echo"</pre>";

$controller->handleRequest();