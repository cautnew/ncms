<?php

namespace Core\NCMS\Dataset;

use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use Core\Route\Request;
use Core\Route\Response;
use HTML\P;
use HTML\SCRIPT;

class DatasetEditPage extends AdminPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/datasets/editdatasets';

  public function __construct(?string $id = null)
  {
    $this->setTitleText('Edit Dataset');
    if (empty($id)) {
      $this->setRoute("/ncms/datasets/list");
    } else {
      $this->setRoute("/ncms/datasets/{$id}/edit");
    }
    $this->setParent(new DatasetPage);
  }

  public function renderPageEdit($id = null): string
  {
    $this->setBodyTitleText('Edit Dataset');

    $this->getCardPrinc()->getBody()->append([
      new P(html: ''),
      DatasetForm::getFormDataset('edit', $id)
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
