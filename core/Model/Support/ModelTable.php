<?php

namespace Core\Model\Support;

use Cautnew\QB\CONDITION as COND;
use Core\Model\ModelCrud;

class ModelTable extends ModelCrud {
  protected string $version = '0.0.1';

  protected bool $indAllowSelect = true;
  protected bool $indAllowDelete = false;
  protected bool $indAllowInsert = false;
  protected bool $indAllowUpdate = false;

  public function __construct() {
    parent::__construct('information_schema.columns', 't');
    $this->setColumns([
      'TABLE_SCHEMA' => 'string',
      'TABLE_NAME' => 'string',
      'COLUMN_NAME' => 'string',
      'DATA_TYPE' => 'string',
      'COLUMN_TYPE' => 'string',
      'COLUMN_KEY' => 'string',
      'EXTRA' => 'string',
      'COLUMN_COMMENT' => 'string'
    ]);
  }

  public function findByTableName(string $tableName): self {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND('TABLE_NAME'))->equals("'$tableName'"));

    return $this;
  }

  public function tableExists(string $tableName): bool {
    $this->findByTableName($tableName);
    $this->select();

    return $this->numSelectedRows() > 0;
  }
}
