<?php

namespace Core\NCMS\Dataset;

use Boot\Constants\DirConstant as DC;
use Core\Dataset\DatasetModelSelect;
use Core\DPG\AdminPage;
use Core\Route\Request;
use Core\Route\Response;
use HTML\A;
use HTML\BS\BREADCRUMB_ITEM;
use HTML\BS\COL;
use HTML\BS\ROW;
use HTML\BUTTON;
use HTML\DIV;
use HTML\FA\ICON_FLOPPY_DISK;
use HTML\FA\ICON_PLUS;
use HTML\FORM;
use HTML\H4;
use HTML\HR;
use HTML\INPUT;
use HTML\LABEL;
use HTML\LI;
use HTML\P;
use HTML\SCRIPT;
use HTML\SPAN;
use HTML\TABLE;
use HTML\TEXTAREA;
use HTML\TBODY;
use HTML\THEAD;
use HTML\TH;
use HTML\TR;
use HTML\UL;

class DatasetDeletePage extends DatasetPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/datasets/datasets-delete';

  public function __construct()
  {
    $this->setTitleText('Datasets');
    $this->setRoute("/ncms/datasets/delete");
    $this->setParent(new DatasetPage);
  }

  public function renderDeletePage(?string $id = null): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE . '-delete.chtml');
    }

    $this->setBodyTitleText('Delete Dataset');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('Datasets', false, '/ncms/datasets'),
      new BREADCRUMB_ITEM('Delete', true)
    ]);

    parent::__construct();

    return $this->render(self::PATH_CACHE . '-delete.chtml');
  }
}
