<?php

namespace Core\Userinfo\User;

use Core\Model\User\ModelUser;

class PasswordReset
{
  private ModelUser $modelUser;

  public function __construct()
  {
    echo "Password Reset";
  }
}
