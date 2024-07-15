<?php

use Boot\Bootstrap;

require_once __DIR__ . '/autoload.php';

echo "-- Recreating system tables --\n";

$bootstrap = new Bootstrap();
$bootstrap->recreateSystemTables();

echo "-- Recreating datasets --\n";
