<?php

use Core\Userinfo\User\User;

require_once __DIR__ . '/../autoload.php';

# Test ModelCRUD

$user = new User();
$user->getModel()->insert([
  'name' => 'Test Name',
  'description' => 'Test Description'
]);
$user->getModel()->commit();