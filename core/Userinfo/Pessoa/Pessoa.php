<?php

namespace Core\Userinfo\Pessoa;

use Core\Model\Pessoa\ModelPessoa;
use Core\Userinfo\Dim\Sexo;

/**
 * Class Pessoa
 */
class Pessoa
{
  private ModelPessoa $model;
  private Sexo $sexo;

  public function __construct()
  {
    $this->model = new ModelPessoa();
  }

  public function getModel(): ModelPessoa
  {
    return $this->model;
  }

  public function getObjSexo(): Sexo
  {
    return $this->sexo;
  }
}
