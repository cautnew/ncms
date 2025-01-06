<?php

namespace App\Services\MediaUpload;

// Reference: https://code-boxx.com/compress-images-php/

class ImageCompress
{
  private string $from;
  private string $extFrom;
  private string $to;
  private string $extTo;

  private static const ALLOWED_FORMATS = ["jpeg", "jpg", "gif", "png", "bmp", "webp"];
  private static const ALLOWED_FORMATS_FROM = ["jpeg", "jpg", "gif", "png", "bmp", "webp"];
  private static const ALLOWED_FORMATS_TO = ["jpeg", "jpg", "gif", "png", "webp"];

  public function __construct (string $from, string $to) {
    $this->setFrom($from);
    $this->setTo($to);
  }

  public function setFrom (?string $from): self {
    if (empty($from)) {
      return $this;
    }

    $this->from = $from;
    $this->extFrom = strtolower(pathinfo($from, PATHINFO_EXTENSION));

    return $this;
  }

  public function setTo (?string $to): self {
    if (empty($to)) {
      return $this;
    }

    $this->to = $to;
    $this->extTo = strtolower(pathinfo($to, PATHINFO_EXTENSION));

    return $this;
  }

  public function getFrom (): string {
    return $this->from ?? '';
  }

  public function getExtFrom (): string {
    return $this->extFrom ?? '';
  }

  public function getTo (): string {
    return $this->to ?? '';
  }

  public function getExtTo (): string {
    return $this->extTo ?? '';
  }

  public static function compress (string $from, string $to, int $max_width=null, $max_height=null, $quality=null): bool {
    // (A) CHECKS
    // (A1) SOURCE IMAGE IS READABLE
    if (!is_readable($from)) { throw new \InvalidArgumentException("Cannot read {$from}"); }
  
    // (A2) ALLOWED IMAGE FILE FORMATS
    $extFrom = strtolower(pathinfo($from, PATHINFO_EXTENSION));
    $extTo = strtolower(pathinfo($to, PATHINFO_EXTENSION));
    if (!in_array($extFrom, self::ALLOWED_FORMATS_FROM)) {
      throw new \Exception("{$from} - Invalid file format", 1);
    }
    if (!in_array($extTo, self::ALLOWED_FORMATS_TO)) {
      throw new \Exception("{$to} - Invalid file format", 1);
    }
  
    // (B) OPEN SOURCE IMAGE
    $fn = "imagecreatefrom" . ($extFrom=="jpg" ? "jpeg" : $extFrom);
    $img = $fn($from);
  
    // (C) RESIZE IMAGE
    if ($max_width!=null || $max_height!=null) {
      // (C1) SOURCE IMAGE DIMENSIONS
      $sw = imagesx($img);
      $sh = imagesy($img);
  
      // (C2) RESIZE RATIO
      if ($max_width != null && $sw>$max_width) { $rw = $max_width / $sw; } else { $rw = 1; }
      if ($max_height != null && $sh>$max_height) { $rh = $max_height / $sh; } else { $rh = 1; }
  
      // (C3) RESIZE USING THE SMALLER RATIO
      if ($rw!=1 || $rh!=1) {
        $rr = $rw<$rh ? $rw : $rh ;
        $img = imagescale($img, floor($rr * $sw), floor($rr * $sh));
      }
    }
  
    // (D) SAVE & COMPRESS IMAGE
    // jpg : 0 to 100
    // webp : -1 (default), 0 to 100
    // png : -1 (default), 0 (none) to 9
    // gif : na (no compression)
    if ($extTo=="gif") {
      imagegif($img, $to);
    } else {
      $testQualityValue = ($quality==null || !is_numeric($quality));
      $testQuality = function (int $qualityLow, int $qualityHigh) use ($testQualityValue, $quality):bool  {
        return $testQualityValue || $quality<$qualityLow || $quality>$qualityHigh;
      };

      if ($extTo=="jpg" && $testQuality(0, 100)) $quality = 30;
      if ($extTo=="webp" && $testQuality(-1, 100)) $quality = -1;
      if ($extTo=="png" && $testQuality(-1, 9)) $quality = -1;
      $fn = "image" . ($extTo=="jpg" ? "jpeg" : $extTo);
      $fn($img, $to, $quality);
    }

    return true;
  }

  public function execute (?string $from=null, ?string $to=null, ?int $max_width=null, ?int $max_height=null, ?int $quality=null): bool {
    $this->setFrom($from);
    $this->setTo($to);
    return self::compress($from, $to, $max_width, $max_height, $quality);
  }
}
 
// (E) GO!
ImageCompress::compress("D:/http/demo.png", "D:/http/demoC.jpg");
ImageCompress::compress("D:/http/demo.png", "D:/http/demoC.png", 200, 0); // max width 200
ImageCompress::compress("D:/http/demo.png", "D:/http/demoC.gif", 0, 200); // max height 200
ImageCompress::compress("D:/http/demo.png", "D:/http/demoC.webp", 200, 400); // max width 200, max height 400
echo "DONE";