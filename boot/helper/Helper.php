<?php

namespace Boot\Helper;

use Boot\Constants\DatesConstant;

/**
 * Full static class with methods for helping with
 * strings, arrays and more complex functions.
 */
class Helper
{
  public static ArraysHelper $Arrays;
  public static DateHelper $Dates;
  public static FileSystem $FileSystem;

  public static function generateRandomId(string $prefix = null): string
  {
    $newId = self::getCodTimeStamp() . uniqid() . $prefix;
    return $prefix . hash('md5', $newId);
  }

  public static function getCodTimeStamp(): string
  {
    return date(DatesConstant::FORMAT_CODTIMESTAMP);
  }

  /**
   * @param string $uri
   * Valid encoded base64 URI to the file.
   * @return array
   * array = [$file_type, $file_extension, $file_data]
   */
  public static function split_data_from_base64_uri(string $uri): array
  {
    list($file_type, $file_data) = explode(";base64,", $uri);
    list($file_type, $file_extension) = explode("/", $file_type);
  
    return [$file_type, $file_extension, $file_data];
  }
}
