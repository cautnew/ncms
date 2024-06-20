<?php

namespace Core\Model;

class ModelFactory
{
  private ModelTable $modelTable;
  private ModelInsert $modelInsert;
  private ModelSelect $modelSelect;
  private ModelUpdate $modelUpdate;

  public function __construct(?ModelTable $modelTable = null, ?ModelInsert $modelInsert = null, ?ModelSelect $modelSelect = null, ?ModelUpdate $modelUpdate = null)
  {
    $this->setModelTable($modelTable);
    $this->setModelInsert($modelInsert);
    $this->setModelSelect($modelSelect);
    $this->setModelUpdate($modelUpdate);
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

  public function loadDataToInsert(array $data): self
  {
    $this->modelInsert->loadData($data);

    return $this;
  }
}
