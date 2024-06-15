<?php

namespace Core\Model;

class ModelObj
{
  private ModelTable $modelTable;
  private ModelInsert $modelInsert;
  private ModelSelect $modelSelect;
  private ModelUpdate $modelUpdate;

  public function __construct(ModelTable $modelTable, ?ModelSelect $modelSelect = null, ?ModelInsert $modelInsert = null, ?ModelUpdate $modelUpdate)
  {
    $this->setModelTable($modelTable);
    if ($modelSelect !== null) {
      $this->setModelSelect($modelSelect);
    }
    if ($modelInsert !== null) {
      $this->setModelInsert($modelInsert);
    }
    if ($modelUpdate !== null) {
      $this->setModelUpdate($modelUpdate);
    }
  }

  public function getModelTable(): ModelTable
  {
    return $this->modelTable;
  }

  public function setModelTable(ModelTable $modelTable): self
  {
    $this->modelTable = $modelTable;

    return $this;
  }

  public function getModelInsert(): ModelInsert
  {
    return $this->modelInsert;
  }

  public function setModelInsert(ModelInsert $modelInsert): self
  {
    $this->modelInsert = $modelInsert;

    return $this;
  }

  public function getModelSelect(): ModelSelect
  {
    return $this->modelSelect;
  }

  public function setModelSelect(ModelSelect $modelSelect): self
  {
    $this->modelSelect = $modelSelect;

    return $this;
  }

  public function getModelUpdate(): ModelUpdate
  {
    return $this->modelUpdate;
  }

  public function setModelUpdate(ModelUpdate $modelUpdate): self
  {
    $this->modelUpdate = $modelUpdate;

    return $this;
  }
}
