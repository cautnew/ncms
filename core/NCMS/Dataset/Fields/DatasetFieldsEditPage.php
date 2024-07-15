<?php

namespace Core\NCMS\Dataset\Fields;

use Boot\Constants\DirConstant as DC;
use Core\Dataset\DatasetModelSelect;
use Core\DPG\AdminPage;
use Core\NCMS\Dataset\DatasetController;
use Core\NCMS\Dataset\DatasetForm;
use Core\NCMS\Dataset\DatasetEditPage;
use Core\Route\Request;
use Core\Route\Response;
use HTML\DIV;
use HTML\INPUT_TEXT;
use HTML\LABEL;
use HTML\P;
use HTML\SPAN;
use HTML\SCRIPT;

class DatasetFieldsEditPage extends AdminPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/datasets/fields/fieldspage';

  public function __construct(?string $id = null)
  {
    $this->setTitleText('Edit Fields Dataset');
    if (empty($id)) {
      $this->setRoute("/ncms/datasets/list");
    } else {
      $this->setRoute("/ncms/datasets/fields/{$id}/edit");
    }
    $this->setParent(new DatasetEditPage($id));
  }

  public function renderPageFieldsEdit($id = null): string
  {
    $this->setBodyTitleText('Edit Fields Dataset');
    $this->setRoute("/ncms/datasets/fields/{$id}/edit");
    $this->setParent(new DatasetEditPage($id));

    $datasetController = new DatasetController();
    $datasetInfo = $datasetController->getDatasetInfo($id);

    $this->getCardPrinc()->getBody()->append([
      new DIV('row mb-2', append: [
        new LABEL('dataset-var-name', 'col-sm-2 col-form-label', txt: 'Dataset name:'),
        new DIV('col-sm-10', append: new INPUT_TEXT('form-control-plaintext fw-bold', 'dataset-var-name', readonly: true, value: $datasetInfo->var_name))
      ]),
      // DatasetForm::getFormDataset('edit')
    ]);

    $this->getCardPrinc()->getTag()->appendAfter(new SCRIPT(append: <<<JAVASCRIPT
    const datasetId='{$id}';
    JAVASCRIPT
    ));

    parent::__construct();

    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/assets/js/datasettablefieldsfeatures.js'));
    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/assets/js/datasetinputcontrollerfeatures.js'));
    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/assets/js/datasetseditfeatures.js'));

    return $this->render();
  }

  public function postEdit(?string $id = null): string
  {
    Response::setToJsonResponse();
    $controller = new DatasetController;
    $request = new Request();
    $formData = $request->all();

    return json_encode($controller->postEdit($id, $formData));
  }
}
