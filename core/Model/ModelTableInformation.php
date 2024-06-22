<?php

namespace Core\Model;

use QB\CONDITION as COND;
use Core\Model\ModelCRUD;

class ModelTableInformation
{
  protected ModelSelect $modelSelectInformationSchema;
  protected string $version = '0.0.1';

  public function __construct()
  {
    $this->modelSelectInformationSchema = new ModelSelect();
    // parent::__construct('information_schema.columns', 't');
    $this->setColumns([
      'TABLE_SCHEMA',
      'TABLE_NAME',
      'COLUMN_NAME',
      'DATA_TYPE',
      'COLUMN_TYPE',
      'COLUMN_KEY',
      'EXTRA',
      'COLUMN_COMMENT',
    ]);
  }

  public function findByTableName(string $tableName): self
  {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND('TABLE_NAME'))->equals("'$tableName'"));

    return $this;
  }

  public function findBySchemaTableName(string $schema, string $tableName): self
  {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND('TABLE_SCHEMA'))->equals("'$schema'"))
      ->and((new COND('TABLE_NAME'))->equals("'$tableName'"));

    return $this;
  }

  public function tableExists(string $schema, string $tableName): bool
  {
    $this->findBySchemaTableName($schema, $tableName);
    $this->select();

    return $this->numSelectedRows() > 0;
  }
}
