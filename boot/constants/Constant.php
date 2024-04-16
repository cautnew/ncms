<?php

namespace Boot\Constants;

readonly class Constant
{
  public DatesConstant $Dates;

  public const DEFAULT_CHARSET = 'UTF-8';
  public const DEFAULT_LANGUAGE = 'pt-br';

  public const LIMIT_MINUTES_ALLOWED_TO_WORK_PER_DAY = 660;

  public const BITS_IN_BYTES = 8;
  public const BYTES_IN_KB = 1024;
  public const BYTES_IN_MB = 1048576;
  public const BYTES_IN_GB = 1073741824;
  public const BYTES_IN_TB = 1099511627776;

  public const SECONDS_IN_MINUTE = 60;
  public const MINUTES_IN_HOUR = 60;
  public const MINUTES_IN_DAY = 1440;
  public const MINUTES_IN_MONTH = 43200;
  public const MINUTES_IN_YEAR = 525600;

  public const URL_TELEGRAM_BOT = "https://api.telegram.org/bot";

  public const PW_MIN_LEN =  8;
  public const PW_MAX_LEN =  40;
  public const PW_DEFAULT_ALGO =  PASSWORD_DEFAULT;
  public const PW_OPTION =  ["cost" => 10];

  public const DB_NAME = 'syslefe';

  public const COD_NIVEL_ADM = '6c4361bd3aafb49cb8215420aa53ca58';
  public const COD_NIVEL_USR = '70bf7ee48326071a373a464730002dc1';
}
