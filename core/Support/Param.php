<?php

namespace Core\Support;

use Core\Model\Support\ModelParam;

/**
 * Class Param
 */
class Param {
  private ModelParam $modelParam;

  public function __construct() {
    $this->modelParam = new ModelParam();
  }

  public function __get(string $key) {
    $this->findByAliasParam($key);
    return $this->modelParam->val_param;
  }

  public function __set(string $key, string $value) {
    $this->findByAliasParam($key);
    if ($this->modelParam->isEmpty()) {
      $this->modelParam->startInsertingMode();
      $this->modelParam->alias_param = $key;
      $this->modelParam->val_param = $value;
      $this->modelParam->insert()->commitInsert();
      $this->modelParam->stopInsertingMode();

      return;
    }

    $this->modelParam->val_param = $value;
    $this->modelParam->update()->commitUpdate();
  }

  public function getModel(): ModelParam {
    return $this->modelParam;
  }

  public function setDscParam(string $aliasParam, string $dscParam): self {
    $this->modelParam->findByAliasParam($aliasParam);
    $this->modelParam->dsc_param = $dscParam;
    return $this;
  }

  public function findByAliasParam(string $aliasParam): self {
    $this->modelParam->findByAliasParam($aliasParam);
    $this->modelParam->select();

    return $this;
  }

  public function delete(string $aliasParam): self {
    $this->modelParam->findByAliasParam($aliasParam);
    $this->modelParam->delete()->commitDelete();

    return $this;
  }
}
