<?php

namespace Boot;

use Core\Dataset\DatasetFieldsModelTable;
use Core\Dataset\DatasetFieldTypesModelTable;
use Core\Dataset\DatasetModelTable;

class Bootstrap
{
  private array $systemModelTables = [];

  public function __construct()
  {
    $this->systemModelTables = [
      'Dataset' => new DatasetModelTable,
      'DatasetFields' => new DatasetFieldsModelTable,
      'DatasetFieldTypes' => new DatasetFieldTypesModelTable
    ];
  }

  public function getSystemModelTables(): array
  {
    return $this->systemModelTables;
  }

  public function setSystemModelTables(array $systemModelTables): void
  {
    $this->systemModelTables = $systemModelTables;
  }

  public function recreateSystemTables(): void
  {
    foreach ($this->systemModelTables as $modelTable => $modelTableClass) {
      $modelTable->createTable();
    }
  }
}
