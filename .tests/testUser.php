<?php

use Core\Lang\LanguageModelTable;
use Core\Userinfo\User\UserModelTable;

require_once __DIR__ . '/../autoload.php';

$lang = new LanguageModelTable();
$lang->drop();

# Test ModelCRUD
$user = new UserModelTable();
$user->create();
$user->createTriggers();
