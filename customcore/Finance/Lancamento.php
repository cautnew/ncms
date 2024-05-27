<?php

namespace CustomCore\Finance;

use CustomCore\Finance\ModelLancamento;
use Core\Support\Session;

class Lancamento
{
  private ModelLancamento $model;
  private string $cod_user;

  public function __construct()
  {
    $ses = new Session();
    $this->model = new ModelLancamento();

    $this->cod_user = $ses->cod_user ?? '';
  }

  public function startTable(): void
  {
    $this->model->dropTable();
    $this->model->createTable();
    $this->model->createTriggers();
  }

  public function getLancamentos(?string $cod_user = null): array
  {
    if (empty($cod_user)) {
      $cod_user = $this->cod_user;
    }

    $this->model->select();

    return $this->model->getCurrentData();
  }
}
