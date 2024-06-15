<?php

use Core\Userinfo\Dim\SexModelSelect;

require_once __DIR__ . '/../autoload.php';

$sexModelSelect = new SexModelSelect();
$sexModelSelect->select();
var_dump($sexModelSelect->getData());
$sexModelSelect->next();
var_dump($sexModelSelect->getData());

echo "\nThe value of var_lang is: {$sexModelSelect->var_lang}\n";
