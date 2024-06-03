<?php

namespace Core\Route;

use Boot\Constants\Constant as C;
use Core\Route\Exceptions\InvalidHttpStatusException;

class Response
{
  protected string $contentType;
  protected static int $status;
  protected static bool $isjsonresponse;
  protected static bool $istxtresponse;
  protected static bool $iscsvresponse;

  public static function setStatus(int $status): void
  {
    if ($status < 100 || $status > 599) {
      self::$status = 200;

      throw new InvalidHttpStatusException("HTTP status code must be between 100 and 599. Status code '$status' provided.");
    }

    self::$status = $status;
  }

  public static function getStatus(): int
  {
		if (!isset(self::$status)) {
			self::setStatus(200);
		}

		return self::$status;
  }

  public function content()
  {}

  public static function setTimeLimit(int $time): void
  {
    set_time_limit($time);
  }

  public static function setToUnlimitTime(): void
  {
    self::setTimeLimit(0);
  }

  public static function setToUnlimitedTime(): void
  {
    self::setTimeLimit(0);
  }

  public static function setToNoTimeLimit(): void
  {
    self::setTimeLimit(0);
  }

  protected static function setContentTypeResponse(string $type, string $charset = C::DEFAULT_CHARSET): void
  {
    $header = "Content-Type:{$type}";

    if (!empty($charset)) {
      $header .= ";charset=\"{$charset}\"";
    }

    header($header);
  }

  protected static function setContentLengthResponse(int $bytes): void
  {
    header("Content-Length:{$bytes}");
  }

  public static function setToJsonResponse(string $charset = C::DEFAULT_CHARSET): void
  {
    self::setContentTypeResponse('application/json', $charset);
    self::$isjsonresponse = true;
    self::$istxtresponse = false;
    self::$iscsvresponse = false;
  }

  public static function setToTxtResponse(string $charset = C::DEFAULT_CHARSET): void
  {
    self::setContentTypeResponse('application/text', $charset);
    self::$isjsonresponse = false;
    self::$istxtresponse = true;
    self::$iscsvresponse = false;
    self::setToUnlimitedTime();
  }

  public static function setToCsvResponse(string $charset = C::DEFAULT_CHARSET): void
  {
    self::setContentTypeResponse('application/csv', $charset);
    self::$isjsonresponse = false;
    self::$istxtresponse = false;
    self::$iscsvresponse = true;
    self::setToUnlimitedTime();
  }

  public static function setToOctetResponse(string $filename, int $bytes): void
  {
    self::setContentTypeResponse('octet/stream');
    self::setToAttachmentResponse($filename);
    self::setContentLengthResponse($bytes);
    self::$isjsonresponse = false;
    self::$istxtresponse = false;
    self::$iscsvresponse = true;
    self::setToUnlimitedTime();
  }

  public static function setToAttachmentResponse(string $filename): void
  {
    $header = "Content-Disposition:attachment";

    if (!empty($filename)) {
      $filename = str_replace(' ', '_', $filename);
      $header .= ";filename=\"$filename\"";
    }

    header($header);
    self::setToUnlimitedTime();
  }

  public static function setToDownloadResponse(string $filename): void
  {
    self::setToAttachmentResponse($filename);
  }

  public static function setToDownloadCSVResponse(string $filename, ?string $charset = C::DEFAULT_CHARSET): void
  {
    self::setToCsvResponse($charset);
    self::setToAttachmentResponse($filename);
  }

  public static function redirectTo(string $location, array $args=[]): void
  {
    if (!empty($args)) {
      $indArgs = strpos($location, '?');
      if ($indArgs === false) {
        $location .= "?";
      } else {
        $location = rtrim($location, '&') . '&';
      }
      $location .= http_build_query($args);
    }

    header("Location:$location", true);

    die();
  }
}
