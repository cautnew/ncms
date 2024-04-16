<?php

namespace Boot\Helper;

/**
 * Class used to calculate Fibonacci sequence elements.
 */
class Fibonacci
{
  public const PATH_STORED_VALUES = PSHARED . "/files/fibseq";
  private const BCSCALEPRECISION = 50;

  public static function is_calculated(int $num): bool
  {
    $path_val = self::PATH_STORED_VALUES . "/$num.fbn";
    return file_exists($path_val);
  }

  public static function get_calculated_value(int $num): string
  {
    if (!self::is_calculated($num)) {
      return '';
    }

    $path_val = self::PATH_STORED_VALUES . "/$num.fbn";
    $val = file_get_contents($path_val);
    return $val;
  }

  public static function save_calculated_value(int $num, string $val): bool
  {
    $path_val = self::PATH_STORED_VALUES . "/$num.fbn";
    file_put_contents($path_val, $val);
    return true;
  }

  public static function get(int $num): string
  {
    return self::calc($num);
  }

  /**
   * Calculate element value of Fibonacci sequence.
   * Fibonacci formula is: a(n) = (((1 + sqrt(5)) / 2) ^ n - ((1 - sqrt(5)) / 2) ^ n) / sqrt(5)
   * 
   * @param int $num
   * Element to be calculated
   * @return string
   * Value calculated for the $num Fibonacci sequence element.
   */
  public static function calc(int $num): string
  {
    if ($num < 1) {
      return 0;
    }

    if ($num <= 2) {
      return 1;
    }

    $calc_val = self::get_calculated_value($num);

    if (!empty($calc_val)) {
      return $calc_val;
    }

    $ant = self::get_calculated_value($num - 1);
    $dant = self::get_calculated_value($num - 2);

    if (!empty($ant) && !empty($dant)) {
      return bcadd($ant, $dant);
    }

    bcscale(self::BCSCALEPRECISION);

    $sqrt5 = bcsqrt(5);

    $fatAdd = bcadd(1, $sqrt5);
    $fatSub = bcsub(1, $sqrt5);

    $fat1 = bcdiv($fatAdd, 2);
    $fat2 = bcdiv($fatSub, 2);

    $powFat1 = bcpow($fat1, $num);
    $powFat2 = bcpow($fat2, $num);

    $valSub = bcsub($powFat1, $powFat2);
    $valPrecision = bcdiv($valSub, $sqrt5, 1);

    list($intPart, $decimalPart) = explode('.', $valPrecision);

    $val = ($decimalPart >= 5) ? bcadd($intPart, 1, 0) : $intPart;
    self::save_calculated_value($num, $val);

    return $val;
  }
}

$fib = new Fibonacci();
$fib->calc(10);
