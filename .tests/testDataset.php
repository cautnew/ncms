<?php

use Core\Dataset\DatasetModelTable;

require_once __DIR__ . '/../autoload.php';

# Test Dataset

$dataset = new DatasetModelTable();
$dataset->recreate();
$dataset->createTriggers();
