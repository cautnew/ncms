<?php

namespace Core\Userinfo\Dim;

use Core\Lang\LanguageModelTable;
use QB\CONDITION as COND;
use Core\Model\ModelTable;
use Boot\Constants\Constant as CNT;

class SexModelTable extends ModelTable
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(CNT::DB_NAME, 'dim_sex', 'dsex');
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
        'length' => 25,
      ],
      'var_abrev' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 25,
      ],
      'chr_cod' => [
        'type' => 'char',
        'is_null' => false,
      ],
      'var_lang' => [
        'type' => 'varchar',
        'is_null' => false,
        'length' => 10,
        'table_reference' => new LanguageModelTable,
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

    $this->addForeignKey('var_lang', new LanguageModelTable, 'var_lang');
  }
}
