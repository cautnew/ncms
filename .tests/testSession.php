<?php

use Core\Support\Session;

require_once __DIR__ . '/../autoload.php';

$ses = new Session();
$ses->set('name', 'Test Name');

echo $ses->name;
