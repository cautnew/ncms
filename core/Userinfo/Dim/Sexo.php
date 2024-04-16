<?php

namespace Core\Userinfo\Dim;

use Cautnew\QB\CONDITION as COND;
use Core\Model\Dim\ModelSexo;

/**
 * Class Sexo
 */
class Sexo {
  private ModelSexo $modelSexo;

  public function __construct() {
    $this->modelSexo = new ModelSexo();
  }

  public function getModel(): ModelSexo {
    return $this->modelSexo;
  }

  public function findByCodSexoAbrev(string $codSexoAbrev): self {
    $this->modelSexo->findByCodAbrev($codSexoAbrev);
    $this->modelSexo->select();

    return $this;
  }
}
