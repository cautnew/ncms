<?php

namespace Core\Support;

use Core\Model\Support\ModelParam;

/**
 * Class Param
 */
class Param
{
  private ModelParam $modelParam;

  public function __construct()
  {
    $this->modelParam = new ModelParam();
  }

  public function __get(string $key)
  {
    $this->findByAliasParam($key);

    if ($this->getModel()->numSelectedRows() > 0) {
      return $this->getModel()->val_param;
    }

    return null;
  }

  public function __set(string $key, string $value)
  {
    $this->findByAliasParam($key);
    if ($this->getModel()->isEmpty()) {
      $this->getModel()->startInsertingMode();
      $this->getModel()->alias_param = $key;
      $this->getModel()->val_param = $value;
      $this->getModel()->dsc_param = $key;
      $this->getModel()->cod_tipo_param = '1';
      $this->getModel()->insert()->commitInsert();
      $this->getModel()->stopInsertingMode();

      return;
    }

    $this->getModel()->val_param = $value;
    $this->getModel()->dsc_param = $key;
    $this->getModel()->update()->commitUpdate();
  }

  public function getModel(): ModelParam
  {
    return $this->modelParam;
  }

  public function setDscParam(string $aliasParam, string $dscParam): self
  {
    $this->modelParam->findByAliasParam($aliasParam);
    $this->modelParam->dsc_param = $dscParam;
    return $this;
  }

  public function findByAliasParam(string $aliasParam): self
  {
    $this->modelParam->findByAliasParam($aliasParam);
    $this->modelParam->select();

    return $this;
  }

  public function delete(string $aliasParam): self
  {
    $this->modelParam->findByAliasParam($aliasParam);
    $this->modelParam->delete()->commitDelete();

    return $this;
  }
}
