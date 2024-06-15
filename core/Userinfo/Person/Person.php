<?php

namespace Core\Userinfo\Person;

use Core\Model\ModelObj;
use Core\Userinfo\Person\ModelPerson;
use Core\Userinfo\Dim\Sexo;

/**
 * Class Pessoa
 */
class Person extends ModelObj
{
  private ModelPerson $model;
  private Sexo $sexo;

  public function __construct()
  {
    $this->model = new ModelPerson();
  }

  public function next(): self
  {
    $this->model->next();
    return $this;
  }

  public function prev(): self
  {
    $this->model->prev();
    return $this;
  }

  public function getModel(): ModelPerson
  {
    return $this->model;
  }
}
