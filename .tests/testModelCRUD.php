<?php

require_once __DIR__ . '/../autoload.php';

# Test ModelCRUD

$model = new Core\Model\ModelCRUD('test_table', 'test');

$model->insert([
  'name' => 'Test Name',
  'description' => 'Test Description'
]);
$model->commitInsert();
