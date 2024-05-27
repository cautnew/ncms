<?php

namespace Core\Userinfo\Dim;

use Core\Model\ModelCRUD;
use QB\CONDITION as COND;
use Boot\Constants\Constant as CNT;

class ModelGender extends ModelCrud
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(CNT::DB_NAME . '.dim_gender', 'gndr');
    $this->setColumns([
      'gndr.cod_gender' => 'string',
      'gndr.dsc_gender' => 'string',
      'gndr.cod_gender_abrev' => 'string',
      'gndr.dat_created' => 'date',
      'gndr.dat_updated' => 'date',
      'gndr.dat_expired' => 'date',
      'gndr.cod_user_created' => 'string',
      'gndr.cod_user_updated' => 'string',
      'gndr.cod_user_expired' => 'string'
    ]);
    $this->setColumnsAllowInsert([
      'cod_sexo' => 'string',
      'dsc_sexo' => 'string',
      'cod_sexo_abrev' => 'string',
      'cod_usuario_criacao' => 'string'
    ]);
    $this->setColumnsAllowUpdate([
      'dsc_sexo' => 'string',
      'cod_sexo_abrev' => 'string',
      'cod_usuario_alteracao' => 'string',
      'cod_usuario_expiracao' => 'string'
    ]);

    $this->setPrimaryKey('cod_gender');

    $this->setTableTriggers([
      'dim_gender_before_insert' => new CREATE_TRIGGER($this->getTableName(), 'dim_gender_before_insert', 'BEFORE', 'INSERT', <<<SQL
      BEGIN
        SET NEW.dat_created = NOW();
        SET NEW.cod_user_created = CURRENT_USER();
        SET NEW.dat_updated = NULL;
        SET NEW.cod_user_updated = NULL;
        SET NEW.dat_expired = NULL;
        SET NEW.cod_user_expired = NULL;
      END
      SQL),
      'dim_gender_before_update' => new CREATE_TRIGGER($this->getTableName(), 'dim_gender_before_update', 'BEFORE', 'UPDATE', <<<SQL
      BEGIN
        SET NEW.dat_created = OLD.dat_created;
        SET NEW.cod_user_created = OLD.cod_user_created;
        SET NEW.dat_updated = NOW();
        SET NEW.cod_user_updated = CURRENT_USER();
      END
      SQL)
    ]);
  }

  public function findByCodAbrev(string $codAbrev): self
  {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND('gndr.cod_gender_abrev'))->equals("'$codAbrev'"));

    return $this;
  }
}
