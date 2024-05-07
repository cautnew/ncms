<?php

namespace Core\Model\Dim;

use Core\Model\ModelCRUD;
use Cautnew\QB\CONDITION as COND;
use Boot\Constants\Constant as CNT;

class ModelSexo extends ModelCrud {
  protected string $version = '0.0.1';

  public function __construct() {
    parent::__construct(CNT::DB_NAME . '.dim_sexo', 'sexo');
    $this->setColumns([
      'sexo.cod_sexo' => 'string',
      'sexo.dsc_sexo' => 'string',
      'sexo.cod_sexo_abrev' => 'string',
      'sexo.dat_criacao' => 'date',
      'sexo.dat_alteracao' => 'date',
      'sexo.dat_expiracao' => 'date',
      'sexo.cod_usuario_criacao' => 'string',
      'sexo.cod_usuario_alteracao' => 'string',
      'sexo.cod_usuario_expiracao' => 'string'
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

    $this->setPrimaryKey('cod_sexo');
  }

  public function findByCodAbrev(string $codAbrev): self {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND('sexo.cod_sexo_abrev'))->equals("'$codAbrev'"));

    return $this;
  }
}
