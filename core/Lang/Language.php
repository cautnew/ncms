<?php

namespace Core\Lang;

use QB\CONDITION as COND;

/**
 * Class Sexo
 */
class Language
{
  private LanguageModelTable $modelTable;
  private LanguageModelInsert $modelInsert;

  public function __construct()
  {
    $this->modelInsert = new LanguageModelInsert();
  }

  public function getModelInsert(): LanguageModelInsert
  {
    return $this->modelInsert;
  }

  public function startTable(): void
  {
    $this->modelTable->dropTable();
    $this->modelTable->createTable();
    $this->modelTable->createTriggers();
  }
}
