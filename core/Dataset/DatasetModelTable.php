<?php

namespace Core\Dataset;

use Boot\Constants\Constant as CNT;
use Core\Model\ModelTable;

class DatasetModelTable extends ModelTable
{
  public function __construct()
  {
    parent::__construct(CNT::DB_NAME, 'ncms_tb_datasets', 'ncms_ds');

    $this->setColumnsDefinitions([
      'var_cid' => [
        'type' => 'varchar',
        'length' => 40,
        'is_null' => false,
        'is_primary_key' => true
      ],
      'var_name' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 40,
        'is_unique' => true
      ],
      'var_controller' => [
        'type' => 'varchar',
        'length' => 40,
        'is_null' => true
      ],
      'bol_enabled' => [
        'type' => 'boolean',
        'is_null' => false,
        'default' => 'true'
      ],
      'bol_admin' => [
        'type' => 'boolean',
        'is_null' => false,
        'default' => 'false'
      ],
      'bol_system' => [
        'type' => 'boolean',
        'is_null' => false,
        'default' => 'false'
      ],
      'txt_description' => [
        'type' => 'text',
        'is_null' => true
      ],
      'dtm_created' => [
        'type' => 'datetime',
        'default' => 'null',
        'is_null' => true
      ],
      'dtm_updated' => [
        'type' => 'datetime',
        'default' => 'null',
        'is_null' => true
      ],
      'dtm_expired' => [
        'type' => 'datetime',
        'default' => 'null',
        'is_null' => true
      ],
      'var_user_created' => [
        'type' => 'varchar',
        'length' => 40,
      ],
      'var_user_updated' => [
        'type' => 'varchar',
        'length' => 40,
      ],
      'var_user_expired' => [
        'type' => 'varchar',
        'length' => 40,
      ]
    ]);
  }
}
