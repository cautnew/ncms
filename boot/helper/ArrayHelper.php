<?php

namespace Boot\Helper;

/**
 * Full static class with methods for helping with arrays.
 */
class ArraysHelper
{
  /** <p>Associative array from array keys to array values.</p>
   * @param array $keys
   * @param array $values
   * @return array Array with the keys associated to the values.
   */
  public static function combine_keys(array $keys, array $values): array
  {
    $arr = [];
    foreach ($keys as $key) {
      $arr[$key] = (isset($values[$key])) ? $values[$key] : null;
    }

    return $arr;
  }
}
