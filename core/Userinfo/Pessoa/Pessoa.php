<?php

namespace Core\Userinfo\Pessoa;

use Core\Model\Pessoa\ModelPessoa;
use Core\Userinfo\Dim\Sexo;

/**
 * Class Pessoa
 */
class Pessoa {
  private ModelPessoa $modelPessoa;
  private Sexo $sexo;

  public function __construct() {
    $this->modelPessoa = new ModelPessoa();
  }

  public function getModel(): ModelPessoa {
    return $this->modelPessoa;
  }

  public function getObjSexo(): Sexo {
    return $this->sexo;
  }
}
