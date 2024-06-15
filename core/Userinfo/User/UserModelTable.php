<?php

namespace Core\Userinfo\User;

use Core\Conn\DB;
use Core\Userinfo\Person\PersonModelTable;
use Core\Model\ModelTable;
use Boot\Constants\Constant as CNT;

class UserModelTable extends ModelTable
{
  protected string $version = '0.0.1';
  public function __construct()
  {
    parent::__construct(CNT::DB_NAME, 'dim_user', 'user');
    $this->setColumnsDefinitions([
      'var_cid' => [
        'type' => 'varchar',
        'length' => 40,
        'is_null' => false,
        'is_primary_key' => true
      ],
      'var_cid_person' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 40,
        'table_reference' => new PersonModelTable,
      ],
      'var_us' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 35,
      ],
      'var_pw' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 60,
      ],
      'bol_enabled' => [
        'type' => 'boolean',
        'is_null' => false,
        'default' => 'true'
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
      ->addForeignKey('var_cid_person', new PersonModelTable, 'var_cid');

    $this->prepareDefaultTriggers();
  }
}
