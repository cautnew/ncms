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

class DatasetListPage extends AdminPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/datasets/datasets-list.chtml';

  public function __construct()
  {
    $this->setTitleText('Datasets List');
    $this->setRoute("/ncms/datasets/list");
    $this->setParent(new DatasetPage);
  }

  public function renderPageList(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE);
    }

    $this->setBodyTitleText('Datasets');

    $tableList = new TABLE('table table-striped table-responsive table-hover', 'table-dataset-list');
    $tableList->append([
      new THEAD('table-dark', append: new TR(appendList: [
        new TH('text-center', html: 'ID'),
        new TH('text-center', html: 'Name'),
        new TH('text-center', html: 'Active'),
        new TH('text-center', html: 'Admin'),
        new TH('text-center', html: 'System'),
        new TH('text-center', html: 'Description'),
        new TH('text-center', html: 'Actions'),
      ])),
      new TBODY()
    ]);

    $this->getCardPrinc()->getBody()->append([
      new P(html: 'Here you can check all datasets registered in the system.'),
      new DIV('col-12 d-flex justify-content-end mb-2', append: [
        new A('/ncms/datasets/add', class: 'btn btn-success', append: [new ICON_PLUS(), ' New Dataset'])
      ]),
      new DIV(append: $tableList)
    ]);

    parent::__construct(true);

    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/assets/js/loaddatasetlist.js'));

    return $this->render(self::PATH_CACHE);
  }

  public function renderList(?int $limit = 10, ?int $page = 1): string
  {
    Response::setToJsonResponse();

    $datasetSelect = new DatasetModelSelect();
    $datasetSelect->setRowsLimit($limit);

    if ($page < 1) $page = 1;
    $datasetSelect->setOffset(($page - 1) * $limit);

    $datasetSelect->select();

    $responseArr = [
      'datasets' => $datasetSelect->getData()
    ];

    return json_encode($responseArr);
  }

  public function checkController(string $controller = ''): string
  {
    Response::setToJsonResponse();

    $responseArr = [
      'datasets' => [
        'controller' => []
      ]
    ];

    $controller = base64_decode(str_replace('--', '=', $controller));

    if (!class_exists($controller)) {
      $responseArr['datasets']['controller'] = [
        'controller' => $controller,
        'exists' => false,
        'txt' => 'Controller not found.'
      ];
      return json_encode($responseArr);
    }

    /**
     * @var $classController ModelTable
     */
    $classController = new $controller();

    if (get_parent_class($classController) !== 'Core\Model\ModelTable') {
      $responseArr['datasets']['controller'] = [
        'controller' => $controller,
        'exists' => false,
        'txt' => 'Controller is not a ModelTable.'
      ];
      return json_encode($responseArr);
    }

    $responseArr['datasets']['controller'] = [
      'exists' => true,
      'table_schema' => $classController->getSchema(),
      'table_alias' => $classController->getCommonAlias(),
      'table_name' => $classController->getTableName()
    ];

    return json_encode($responseArr);
  }

  public function datasetExistsById(string $id = ''): string
  {
    Response::setToJsonResponse();
    return json_encode([
      'dataset' => []
    ]);
  }

  public function datasetExistsByName(string $name = ''): string
  {
    Response::setToJsonResponse();
    return json_encode([
      'dataset' => []
    ]);
  }

  public function datasetExistsByController(string $name = ''): string
  {
    Response::setToJsonResponse();
    return json_encode([
      'dataset' => []
    ]);
  }
}
