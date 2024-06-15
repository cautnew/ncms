<?php

namespace Core\Model;

class ModelFactory
{
  private ModelTable $modelTable;
  private ModelInsert $modelInsert;
  private ModelSelect $modelSelect;
  private ModelUpdate $modelUpdate;

  public function __construct(ModelTable $modelTable, ModelInsert $modelInsert, ModelSelect $modelSelect, ModelUpdate $modelUpdate)
  {
    $this->modelTable = $modelTable;
    $this->modelInsert = $modelInsert;
    $this->modelSelect = $modelSelect;
    $this->modelUpdate = $modelUpdate;
  }

  public function loadDataToInsert(array $data): self
  {
    $this->modelInsert->loadData($data);

    return $this;
  }
}
