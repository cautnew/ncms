<?php

namespace Core\Model\User;

use Core\Conn\DB;
use Core\Model\ModelCRUD;
use Boot\Constants\Constant as CNT;
use QB\CREATE_TRIGGER;

class ModelUser extends ModelCRUD
{
  protected string $version = '0.0.1';
  public function __construct()
  {
    parent::__construct(CNT::DB_NAME . '.dim_user', 'user');
    $this->setColumnsDefinitions([
      'cod_user' => [
        'type' => 'varchar',
        'length' => '40',
        'is_primary_key' => true
      ],
      'cod_pessoa' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => false
      ],
      'cod_funcionario' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => false
      ],
      'dsc_us' => [
        'type' => 'varchar',
        'length' => '30',
        'is_null' => false
      ],
      'dsc_pw' => [
        'type' => 'varchar',
        'length' => '150',
        'is_null' => false
      ],
      'ind_blocked' => [
        'type' => 'tinyint',
        'default' => 0
      ],
      'dat_blocked' => [
        'type' => 'datetime',
        'default' => 0
      ],
      'dat_created' => [
        'type' => 'datetime',
        'is_null' => false
      ],
      'dat_updated' => [
        'type' => 'datetime',
        'is_null' => true
      ],
      'dat_expired' => [
        'type' => 'datetime',
        'is_null' => true
      ],
      'cod_user_created' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => false
      ],
      'cod_user_updated' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => true
      ],
      'cod_user_expired' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => true
      ]
    ]);

    $this->setColumns([
      'user.cod_user' => 'varchar',
      'user.cod_pessoa' => 'varchar',
      'user.cod_funcionario' => 'varchar',
      'user.dsc_us' => 'varchar',
      'user.dsc_pw' => 'varchar',
      'user.ind_blocked' => 'tinyint',
      'user.dat_blocked' => 'datetime',
      'user.dat_created' => 'datetime',
      'user.dat_updated' => 'datetime',
      'user.dat_expired' => 'datetime',
      'user.cod_user_created' => 'varchar',
      'user.cod_user_updated' => 'varchar',
      'user.cod_user_expired' => 'varchar'
    ]);

    $this->setPrimaryKey('cod_user');

    $this->setTableTriggers([
      'dim_user_before_insert' => new CREATE_TRIGGER($this->getTableName(), 'dim_user_before_insert', 'BEFORE', 'INSERT', <<<SQL
      BEGIN
        SET NEW.dat_created = NOW();
        SET NEW.cod_user_created = CURRENT_USER();
        SET NEW.dat_updated = NULL;
        SET NEW.cod_user_updated = NULL;
        SET NEW.dat_expired = NULL;
        SET NEW.cod_user_expired = NULL;
      END
      SQL),
      'dim_user_before_update' => new CREATE_TRIGGER($this->getTableName(), 'dim_user_before_update', 'BEFORE', 'UPDATE', <<<SQL
      BEGIN
        SET NEW.dat_created = OLD.dat_created;
        SET NEW.cod_user_created = OLD.cod_user_created;
        SET NEW.dat_updated = NOW();
        SET NEW.cod_user_updated = CURRENT_USER();
      END
      SQL)
    ]);
  }
}
