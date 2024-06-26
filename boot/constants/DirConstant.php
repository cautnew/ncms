<?php

namespace Boot\Constants;

//readonly
class DirConstant
{
  public const PBOOT = __DIR__ . '/..';
  public const PROOT = self::PBOOT . '/..';
  public const PAPI = self::PROOT . '/api';
  public const PGLOBALS = self::PROOT . '/vendor';
  public const PSOURCE = self::PROOT . '/source';
  public const PROUTING = self::PROOT . '/routing';
  public const PGLOBALVARS = self::PROOT . '/core';
  public const PSHARED = self::PROOT . '/shared';
  public const PSTORAGE = self::PROOT . '/storage';
  public const PCORE = self::PGLOBALVARS;
  public const PSUPPORT = self::PROOT . '/../support';
  public const PLOGS = self::PSUPPORT . '/logs';
  public const PTMPFILES = self::PSUPPORT . '/tmp_files';

  /**
   * @var PCACHED Full Path for rendered html cache files
   */
  public const PCACHED = self::PROOT . '/.cached';

  /**
   * @var PIMGS Full Path for Images
   */
  public const PIMGS = self::PROOT . '/imgs';

  /**
   * @var PCSS Full Path for CSS Files
   */
  public const PCSS = self::PSHARED . '/styles';

  /**
   * @var PJS Full Path for JS Files
   */
  public const PJS = self::PSHARED . '/scripts';

  /**
   * @var PRIMGS Path Reference for Images
   */
  public const PRIMGS = '/imgs';

  /**
   * @var PRCSS Path Reference CSS Files
   */
  public const PRCSS = '/styles';

  /**
   * @var PRJS Path Reference JS Files
   */
  public const PRJS = '/scripts';
}
