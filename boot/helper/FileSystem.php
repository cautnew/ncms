<?php

namespace Boot\Helper;

/**
 * Full static class with methods for helping with filesystem.
 */
class FileSystem
{
  public static string $path;

  public function __toString()
  {
    return self::$path;
  }

  public static function is_dir(): bool
  {
    return is_dir(self::$path);
  }

  public static function path_join(int|string|array $path, ...$paths): string
  {
    return self::setPath(array_merge((array) $path, $paths));
  }

  public static function abs_path_join(int|string|array $path, ...$paths): string
  {
    return self::setPath(array_merge([''], (array) $path, $paths));
  }

  public static function setPath(int|string|array $path, ...$paths): self
  {
    $pathTree = array_merge((array) $path, $paths);
    self::$path = implode(DIRECTORY_SEPARATOR, $pathTree);

    return new static();
  }
}
