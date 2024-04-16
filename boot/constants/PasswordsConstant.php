<?php

namespace Boot\Constants;

readonly class PasswordsConstant
{
  public const MIN_LEN =  8;
  public const MAX_LEN =  40;
  public const DEFAULT_ALGO =  PASSWORD_DEFAULT;
  public const OPTION =  ["cost" => 10];
}
