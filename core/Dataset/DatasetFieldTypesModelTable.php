<?php

use Boot\Constants\Constant as CNT;
use Core\Model\ModelTable;

class DatasetFieldTypesModelTable extends ModelTable
{
  public function __construct()
  {
    parent::__construct(CNT::DB_NAME, 'ncms_tb_datasets_field_types', 'ncms_dsft');

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
        'length' => 40
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

    $this->prepareDefaultTriggers();
  }
}
