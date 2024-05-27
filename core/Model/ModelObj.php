<?php

namespace Core\Model;

use Core\Model\ModelCRUD;
use Core\Model\ModelTable;

abstract class ModelObj
{
  private ModelCRUD $model;
  private ModelTable $table;

  private string $schema;
  private string $tableName;
  private string $tableAlias;

  public function __construct()
  {
    $this->setSchema('schema_name');
    $this->setTableName('table_name');
    $this->setTableAlias('table_alias');
    $this->table = new ModelTable($this->getSchema(), $this->getTableName());
    $this->model = new ModelCRUD($this->getTableName(), $this->getTableAlias());
  }

  public function getSchema(): string
  {
    return $this->schema;
  }

  public function setSchema(string $schema): self
  {
    $this->schema = $schema;
    return $this;
  }

  public function getTableName(): string
  {
    return $this->tableName;
  }

  public function setTableName(string $tableName): self
  {
    $this->tableName = $tableName;
    return $this;
  }

  public function getTableAlias(): string
  {
    return $this->tableAlias;
  }

  public function setTableAlias(string $tableAlias): self
  {
    $this->tableAlias = $tableAlias;
    return $this;
  }

  public function next(): self
  {
    $this->model->next();
    return $this;
  }

  public function prev(): self
  {
    $this->model->prev();
    return $this;
  }

  public function getModel(): ModelCRUD
  {
    return $this->model;
  }

  public function procedureCreateDataBase(): void
  {
    $this->model->createTable();
  }
}