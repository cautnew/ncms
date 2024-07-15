<?php

namespace Core\NCMS\Dataset;

use Core\Dataset\DatasetModelSelect;
use Core\Dataset\DatasetModelInsert;
use Core\Route\Response;
use Exception;
use stdClass;

class DatasetController
{
  public function postAdd(array $fields = []): array
  {
    $datasetAdd = new DatasetModelInsert();
    $datasetAdd->var_name = $fields['var_name'] ?? '';
    $datasetAdd->var_controller = $fields['var_controller'] ?? '';
    $datasetAdd->txt_description = $fields['txt_description'] ?? '';
    $datasetAdd->bol_enabled = $fields['bol_enabled'] ?? '0';
    $datasetAdd->bol_admin = $fields['bol_admin'] ?? '0';
    $datasetAdd->bol_system = $fields['bol_system'] ?? '0';
    $datasetAdd->insert();

    try {
      $datasetAdd->commit();
    } catch (Exception $e) {
      return [
        'dataset' => [
          'status' => 'error',
          'timestamp' => date('Y-m-d H:i:s'),
          'txt' => 'Error inserting dataset.',
          'internal_message' => $e->getMessage()
        ]
      ];
    }

    $id = $datasetAdd->getLastInsertedId();

    return [
      'dataset' => [
        'status' => 'success',
        'id' => $id,
        'timestamp' => date('Y-m-d H:i:s'),
        'txt' => 'Dataset updated.'
      ]
    ];
  }

  public function postEdit(?string $id = null, array $fields = []): array
  {
    $datasetUpdate = new DatasetModelUpdate();
    $datasetUpdate->var_cid = $id;
    $datasetUpdate->var_name = $fields['var_name'] ?? '';
    $datasetUpdate->var_controller = $fields['var_controller'] ?? '';
    $datasetUpdate->txt_description = $fields['txt_description'] ?? '';
    $datasetUpdate->bol_enabled = $fields['bol_enabled'] ?? '0';
    $datasetUpdate->bol_admin = $fields['bol_admin'] ?? '0';
    $datasetUpdate->bol_system = $fields['bol_system'] ?? '0';
    $datasetUpdate->update();

    try {
      $datasetUpdate->commit();
    } catch (Exception $e) {
      return [
        'dataset' => [
          'status' => 'error',
          'id' => $id,
          'timestamp' => date('Y-m-d H:i:s'),
          'txt' => 'Error updating dataset.',
          'internal_message' => $e->getMessage()
        ]
      ];
    }

    return [
      'dataset' => [
        'status' => 'success',
        'id' => $id,
        'timestamp' => date('Y-m-d H:i:s'),
        'txt' => 'Dataset updated.'
      ]
    ];
  }

  public function jsonDatasetInfo(string $id = ''): string
  {
    Response::setToJsonResponse();

    $responseArr = [
      'dataset' => $this->getDatasetInfo($id)
    ];

    return json_encode($responseArr);
  }

  public function getDatasetInfo(string $id = ''): stdClass
  {
    $datasetSelect = new DatasetModelSelect();
    $datasetSelect->selectById($id)->select();

    return $datasetSelect->getData()[0];
  }

  public function getDatasetInfoList(?int $limit = 10, ?int $page = 1): array
  {
    $datasetSelect = new DatasetModelSelect();
    $datasetSelect->setRowsLimit($limit);

    if ($page < 1) $page = 1;
    $datasetSelect->setOffset(($page - 1) * $limit);

    $datasetSelect->select();

    $responseArr = [
      'datasets' => $datasetSelect->getData()
    ];

    return $responseArr;
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
