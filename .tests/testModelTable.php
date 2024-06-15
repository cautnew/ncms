<?php

use Core\Model\ModelTable;

require_once __DIR__ . '/../autoload.php';

$tablePessoa = new ModelTable('ncms', 'person');
$tableGender = new ModelTable('ncms', 'gender');
$tableSexo = new ModelTable('ncms', 'sex');

$tableSexo->setColumnsDefinitions([
  'cid' => [
    'type' => 'varchar',
    'length' => 40,
    'is_null' => false,
    'is_primary_key' => true
  ],
  'sex' => [
    'type' => 'varchar',
    'is_null' => false,
    'length' => 25,
  ],
  'abrev' => [
    'type' => 'varchar',
    'is_null' => false,
    'length' => 25,
  ],
  'cod' => [
    'type' => 'char',
    'is_null' => false,
  ],
  'dat_created' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'dat_updated' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'dat_expired' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'cod_user_created' => [
    'type' => 'varchar',
    'length' => 40,
  ],
  'cod_user_updated' => [
    'type' => 'varchar',
    'length' => 40,
  ],
  'cod_user_expired' => [
    'type' => 'varchar',
    'length' => 40,
  ]
]);

$tableGender->setColumnsDefinitions([
  'cid' => [
    'type' => 'varchar',
    'length' => 40,
    'is_null' => false,
    'is_primary_key' => true
  ],
  'gender' => [
    'type' => 'varchar',
    'is_null' => false,
    'length' => 25,
  ],
  'abrev' => [
    'type' => 'varchar',
    'is_null' => false,
    'length' => 25,
  ],
  'cod' => [
    'type' => 'char',
    'is_null' => false,
  ],
  'dat_created' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'dat_updated' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'dat_expired' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'cod_user_created' => [
    'type' => 'varchar',
    'length' => 40,
  ],
  'cod_user_updated' => [
    'type' => 'varchar',
    'length' => 40,
  ],
  'cod_user_expired' => [
    'type' => 'varchar',
    'length' => 40,
  ]
]);

$tablePessoa->setColumnsDefinitions([
  'cid' => [
    'type' => 'varchar',
    'length' => 40,
    'is_null' => false,
    'is_primary_key' => true
  ],
  'str_name' => [
    'type' => 'varchar',
    'length' => 25,
  ],
  'str_lastname' => [
    'type' => 'varchar',
    'length' => 25,
  ],
  'str_midname' => [
    'type' => 'varchar',
    'length' => 50,
  ],
  'str_fullname' => [
    'type' => 'varchar',
    'length' => 100,
  ],
  'dat_birtdate' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'num_docid' => [
    'type' => 'varchar',
    'length' => 30,
  ],
  'cid_gender' => [
    'type' => 'varchar',
    'length' => 40,
  ],
  'cid_sexo' => [
    'type' => 'varchar',
    'length' => 40,
  ],
  'dat_created' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'dat_updated' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'dat_expired' => [
    'type' => 'date',
    'default' => 'null',
    'is_null' => true
  ],
  'cod_user_created' => [
    'type' => 'varchar',
    'length' => 40,
  ],
  'cod_user_updated' => [
    'type' => 'varchar',
    'length' => 40,
  ],
  'cod_user_expired' => [
    'type' => 'varchar',
    'length' => 40,
  ]
]);

$tableSexo->create();
$tableGender->create();
$tablePessoa->create();
