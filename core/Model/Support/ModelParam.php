<?php

namespace Core\Model\Support;

use Cautnew\QB\CONDITION as COND;
use Core\Model\ModelCRUD;
use Boot\Constants\Constant as CNT;

class ModelParam extends ModelCrud
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(CNT::DB_NAME . '.aux_param', 'pram');
    $this->setColumns([
      'pram.cod_param' => 'string',
      'pram.cod_tipo_param' => 'int',
      'pram.alias_param' => 'string',
      'pram.dsc_param' => 'string',
      'pram.val_param' => 'string',
      'pram.val_param_long' => 'string',
      'pram.dat_criacao' => 'date',
      'pram.dat_alteracao' => 'date'
    ]);
    $this->setColumnsAllowInsert([
      'cod_param' => 'string',
      'cod_tipo_param' => 'int',
      'alias_param' => 'string',
      'dsc_param' => 'string',
      'val_param' => 'string',
      'val_param_long' => 'string',
      'dat_criacao' => 'date'
    ]);
    $this->setColumnsAllowUpdate([
      'cod_tipo_param' => 'int',
      'alias_param' => 'string',
      'dsc_param' => 'string',
      'val_param' => 'string',
      'val_param_long' => 'string',
      'dat_alteracao' => 'date'
    ]);

    $this->setPrimaryKey('cod_param');

    $this->setRowsLimit(1);
  }

  public function findByAliasParam(string $aliasParam): self
  {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND('pram.alias_param'))->equals("'$aliasParam'"));

    return $this;
  }
}
