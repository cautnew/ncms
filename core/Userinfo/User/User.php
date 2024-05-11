<?php

namespace Core\Userinfo\User;

use Core\Model\User\ModelUser;

class User
{
  private ModelUser $model;

  public function __construct()
  {
    echo "User";
  }

  public function startTable(): void
  {
    $this->model->dropTable();
    $this->model->createTable();
    $this->model->createTriggers();
  }
}
