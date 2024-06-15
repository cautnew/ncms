<?php

namespace Core\Userinfo\Person;

use QB\CONDITION as COND;
use Core\Model\ModelTable;
use Boot\Constants\Constant as CNT;
use Core\Lang\LanguageModelTable;
use Core\Userinfo\Dim\SexModelTable;
use Core\Userinfo\Dim\GenderModelTable;

class PersonModelTable extends ModelTable
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(CNT::DB_NAME, 'dim_person', 'pers');
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
        'length' => 35,
      ],
      'var_lastname' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 35,
      ],
      'var_fullname' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 35,
      ],
      'var_cid_sex' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 40,
        'table_reference' => new SexModelTable,
      ],
      'var_cid_gender' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 40,
        'table_reference' => new GenderModelTable,
      ],
      'var_lang' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 10,
        'table_reference' => new LanguageModelTable,
      ],
      'dat_birthday' => [
        'type' => 'date',
        'default' => 'null',
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
      ->addForeignKey('var_cid_sex', new SexModelTable, 'var_cid')
      ->addForeignKey('var_cid_gender', new GenderModelTable, 'var_cid')
      ->addForeignKey('var_lang', new LanguageModelTable, 'var_lang');

    $this->prepareDefaultTriggers();
  }
}
