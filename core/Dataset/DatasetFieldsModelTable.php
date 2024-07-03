<?php

namespace Core\Dataset;

use Boot\Constants\Constant as CNT;
use Core\Dataset\DatasetModelTable;
use Core\Dataset\DatasetFieldTypesModelTable;
use Core\Model\ModelTable;

class DatasetFieldsModelTable extends ModelTable
{
  public function __construct()
  {
    parent::__construct(CNT::DB_NAME, 'ncms_tb_datasets_fields', 'ncms_dsf');

    $this->setColumnsDefinitions([
      'var_cid' => [
        'type' => 'varchar',
        'length' => 40,
        'is_null' => false,
        'is_primary_key' => true
      ],
      'var_cid_ds' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 40,
        'table_reference' => new DatasetModelTable,
      ],
      'var_name' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 40
      ],
      'var_cid_type' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 40,
        'table_reference' => new DatasetFieldTypesModelTable,
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

    $this
      ->addForeignKey('var_cid_ds', new DatasetModelTable, 'var_cid')
      ->addForeignKey('var_cid_type', new DatasetFieldTypesModelTable, 'var_cid');

    $this->prepareDefaultTriggers();
  }
}
