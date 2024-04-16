<?php

use Boot\Constants\DirConstant as DC;

readonly class AutoloadListDirectories {
  public const DEFAULT_DIRECTORIES = [
    'Boot\\' => DC::PBOOT,
    'Boot\\Constants\\' => DC::PBOOT . '/constants',
    'Boot\\Exceptions\\' => DC::PBOOT . '/exceptions',
    'Boot\\Helper\\' => DC::PBOOT . '/helper',
    'Cautnew\\QB\\' => DC::PGLOBALS . '/cautnew/qb/src',
    'Cautnew\\HTML\\' => DC::PGLOBALS . '/cautnew/html/src',
    'Cautnew\\HTML\\BS\\' => DC::PGLOBALS . '/cautnew/html/src/BS',
    'Core\\' => DC::PCORE,
    'Core\\BarTop\\' => DC::PCORE . '/BarTop',
    'Core\\Conn\\' => DC::PCORE . '/Conn',
    'Core\\Conn\\Exceptions\\' => DC::PCORE . '/Conn/exceptions',
    'Core\\Clock\\' => DC::PCORE . '/Clock',
    'Core\\Clock\\Exceptions\\' => DC::PCORE . '/Clock/exceptions',
    'Core\\Dim\\' => DC::PCORE . '/Dim',
    'Core\\DPG\\' => DC::PCORE . '/DPG',
    'Core\\Empresa\\' => DC::PCORE . '/Empresa',
    'Core\\Route\\' => DC::PCORE . '/Route',
    'Core\\Route\\Exceptions\\' => DC::PCORE . '/Route/exceptions',
    'Core\\SideMenu\\' => DC::PCORE . '/SideMenu',
    'Core\\Support\\' => DC::PCORE . '/Support',
    'Core\\UserInfo\\' => DC::PCORE . '/UserInfo',
    'Core\\UserInfo\\Exceptions\\' => DC::PCORE . '/UserInfo/exceptions',
    'League\\Plates\\' => DC::PGLOBALS . '/league/plates/src',
    'MatthiasMullie\\PathConverter\\' => DC::PGLOBALS . '/matthiasmullie/path-converter/src',
    'MatthiasMullie\\Minify\\' => DC::PGLOBALS . '/matthiasmullie/minify/src',
    'PHPMailer\\PHPMailer\\' => DC::PGLOBALS . '/phpmailer/phpmailer/src',
    'Source\\' => DC::PSOURCE,
    'thiagoalessio\\TesseractOCR\\' => DC::PGLOBALS . '/thiagoalessio/tesseract_ocr/src'
  ];
}
