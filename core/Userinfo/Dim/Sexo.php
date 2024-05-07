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

  public function getCodSexo(string $codSexoAbrev = null): ?string {
    if (!empty($codSexoAbrev)) {
      $this->findByCodSexoAbrev($codSexoAbrev);
    }

    if ($this->getModel()->numSelectedRows() >= 0) {
      return $this->getModel()->cod_sexo;
    }

    return null;
  }

  public function findByCodSexoAbrev(string $codSexoAbrev): self {
    $this->getModel()->findByCodAbrev($codSexoAbrev);
    $this->getModel()->select();

    return $this;
  }
}
