<?php

namespace Core\Userinfo\Dim;

use QB\CONDITION as COND;
use Core\Userinfo\Dim\SexoModelCRUD;

/**
 * Class Sexo
 */
class Sexo
{
  private SexoModelCRUD $model;

  public function __construct()
  {
    $this->model = new SexoModelCRUD();
  }

  public function getModel(): SexoModelCRUD
  {
    return $this->model;
  }

  public function startTable(): void
  {
    $this->model->dropTable();
    $this->model->createTable();
    $this->model->createTriggers();
  }

  public function getCodSexo(string $codSexoAbrev = null): ?string
  {
    if (!empty($codSexoAbrev)) {
      $this->findByCodSexoAbrev($codSexoAbrev);
    }

    if ($this->getModel()->numSelectedRows() >= 0) {
      return $this->getModel()->cod_sexo;
    }

    return null;
  }

  public function findByCodSexoAbrev(string $codSexoAbrev): self
  {
    $this->getModel()->findByCodAbrev($codSexoAbrev);
    $this->getModel()->select();

    return $this;
  }
}
