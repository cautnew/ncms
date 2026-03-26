<?php

namespace App\Enums;

enum RequestMethods: int
{
  case GET = 1;
  case POST = 2;
  case PUT = 3;
  case PATCH = 4;
  case DELETE = 5;
  case OPTIONS = 6;
  case HEAD = 7;
  case TRACE = 8;

  public static function getByCode(int $code): self
  {
    return match ($code) {
      self::GET => 'GET',
      self::POST => 'POST',
      self::PUT => 'PUT',
      self::PATCH => 'PATCH',
      self::DELETE => 'DELETE',
      self::OPTIONS => 'OPTIONS',
      self::HEAD => 'HEAD',
      self::TRACE => 'TRACE',
      default => 'Unknown',
    };
  }
}
