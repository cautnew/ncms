<?php

namespace App\Services\Templates;

class TemplatesManager
{
  private static $pathJsonConfig = 'Templates/ncms/.config';

  public static function getPathJsonConfig(): string
  {
    $path = app_path(self::$pathJsonConfig);
    return $path;
  }

  public static function getJsonConfig(): string
  {
    $json = file_get_contents(self::getPathJsonConfig());
    return $json;
  }

  public static function getJsonObject(): array
  {
    $jsonObject = json_decode(self::getJsonConfig(), true);
    return $jsonObject;
  }

  public static function saveIntoJsonObject(array $jsonObject): void
  {
    $jsonUpdated = json_encode($jsonObject);
    file_put_contents(self::getPathJsonConfig(), $jsonUpdated);
  }
}
