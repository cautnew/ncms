<?php

namespace Core\Userinfo\Dim;

use Core\Model\ModelCRUD;
use QB\CONDITION as COND;
use Boot\Constants\Constant as CNT;

class SexoModelCRUD extends ModelCRUD
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(CNT::DB_NAME . '.gender', 'sexo');
    $this->setColumns([
      'sexo.cod_sexo',
      'sexo.dsc_sexo',
      'sexo.cod_sexo_abrev',
      'sexo.dat_criacao',
      'sexo.dat_alteracao',
      'sexo.dat_expiracao',
      'sexo.cod_usuario_criacao',
      'sexo.cod_usuario_alteracao',
      'sexo.cod_usuario_expiracao',
    ]);

    $this->setColumnsAllowInsert([
      'cod_sexo',
      'dsc_sexo',
      'cod_sexo_abrev',
      'cod_usuario_criacao'
    ]);

    $this->setColumnsAllowUpdate([
      'dsc_sexo',
      'cod_sexo_abrev',
      'cod_usuario_alteracao',
      'cod_usuario_expiracao'
    ]);

    $this->setPrimaryKey('cod_sexo');
  }

  public function findByCodAbrev(string $codAbrev): self
  {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND('sexo.cod_sexo_abrev'))->equals("'$codAbrev'"));

    return $this;
  }
}
