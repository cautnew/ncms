<?php

namespace Core\NCMS\Dataset;

use Core\Dataset\DatasetModelSelect;
use Core\DPG\AdminPage;
use Core\NCMS\HomeNCMS;
use Core\Route\Request;
use Core\Route\Response;

class DatasetPage extends AdminPage
{
  public function __construct(bool $isSub = false)
  {
    $this->setTitleText('Datasets');
    $this->setRoute("/ncms/datasets");
    $this->setParent(new HomeNCMS);

    if ($isSub) {
      parent::__construct();
    }
  }

  public function renderPage(): string
  {
    return (new DatasetListPage())->renderPageList();
  }

  public function renderPageAdd(): string
  {
    return '';
  }

  public function renderPageEdit($id = null): string
  {
    return '';
  }

  public function renderPageView(?string $id = null): string
  {
    return '';
  }

  public function renderPageDelete(?string $id = null): string
  {
    return '';
  }

  public function renderList(?int $step = null, ?int $page = null): string
  {
    Response::setToJsonResponse();

    $datasetSelect = new DatasetModelSelect();
    $datasetSelect->select();

    $responseArr = [
      'datasets' => []
    ];

    foreach ($datasetSelect->getData() as $dataset) {
      $responseArr['datasets'][] = [
        'var_cid' => $dataset->var_cid,
        'var_name' => $dataset->var_name,
        'var_fields' => '...',
        'txt_description' => $dataset->txt_description,
        'bol_active' => $dataset->bol_enabled === 1,
      ];
    }

    return json_encode($responseArr);
  }

  public function postEdit(?string $id = null): string
  {
    Response::setToJsonResponse();
    $request = new Request();
    $formData = $request->all();

    return json_encode([
      'dataset' => [$formData],
      'dataset_id' => $id
    ]);
  }

  public function datasetInfo(string $id = ''): string
  {
    Response::setToJsonResponse();

    $datasetSelect = new DatasetModelSelect();
    $datasetSelect->selectById($id)->select();

    $responseArr = [
      'dataset' => $datasetSelect->getData()[0]
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
