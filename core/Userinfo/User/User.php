<?php

namespace Core\Userinfo\User;

use Core\Userinfo\User\UserModelTable;

class User
{
  private UserModelTable $modelTable;

  public function getModelTable(): UserModelTable
  {
    return $this->modelTable;
  }

  public function setModelTable(UserModelTable $modelTable): self
  {
    $this->modelTable = $modelTable;

    return $this;
  }
}
