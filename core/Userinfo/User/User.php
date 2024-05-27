<?php

namespace Core\Userinfo\User;

use Core\Userinfo\User\UserModelCRUD;

class User
{
  private UserModelCRUD $model;

  public function __construct()
  {
    $this->model = new UserModelCRUD();
  }

  public function getModel(): UserModelCRUD
  {
    return $this->model;
  }

  public function startTable(): void
  {
    $this->model->dropTable();
    $this->model->createTable();
    $this->model->createTriggers();
  }
}
