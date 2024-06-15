<?php

use Core\Userinfo\Person\Person;

require_once __DIR__ . '/../autoload.php';

# Test ModelCRUD

$person = new Person();
$person->getModel()->insert([
  'name' => 'Test Name',
  'description' => 'Test Description'
]);
$person->getModel()->commit();