<?php

namespace Core\Model\Support;

use Cautnew\QB\CONDITION as COND;
use Core\Model\ModelCRUD;
use Boot\Constants\Constant as CNT;

class ModelLog extends ModelCrud {
  protected string $version = '0.0.1';

  public const ll = 10;

  public function __construct() {
    parent::__construct(CNT::DB_NAME . '.aux_log', 'alog');

    $this->setColumns([
      'alog.cod_log',
      'alog.cod_usuario',
      'alog.cod_tipo_log',
      'alog.dsc_cod_log',
      'alog.dsc_log',
      'alog.dsc_path',
      'alog.dsc_file_class',
      'alog.dat_criacao'
    ]);

    $this->setColumnsAllowInsert([
      'cod_log',
      'cod_usuario',
      'cod_tipo_log',
      'dsc_cod_log',
      'dsc_log',
      'dsc_path',
      'dsc_file_class'
    ]);

    $this->setPrimaryKey('cod_log');

    $this->setRowsLimit(1);
  }

  public function findByTypeParam(int $typeParam): self {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND('alog.alias_param'))->equals("'$typeParam'"));

    return $this;
  }
}
