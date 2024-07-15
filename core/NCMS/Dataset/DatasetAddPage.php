<?php

namespace Core\NCMS\Dataset;

use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use Core\Route\Request;
use Core\Route\Response;
use HTML\P;
use HTML\SCRIPT;

class DatasetAddPage extends AdminPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/datasets/datasets';

  public function __construct()
  {
    $this->setTitleText('Add Dataset');
    $this->setRoute("/ncms/datasets/add");
    $this->setParent(new DatasetPage);
  }

  public function renderPageAdd(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE . '-add.chtml');
    }

    $this->setBodyTitleText('Add Dataset');

    $this->getCardPrinc()->getBody()->append([
      new P(html: 'Fill up the form below to add a new dataset. Pay attention to the fields marked with <strong>*</strong>.'),
      DatasetForm::getFormDataset('add')
    ]);

    parent::__construct();

    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/assets/js/datasettablefieldsfeatures.js'));
    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/assets/js/datasetinputcontrollerfeatures.js'));
    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/assets/js/datasetsaddfeatures.js'));

    return $this->render(self::PATH_CACHE . '-add.chtml');
  }

  public function postAdd(): string
  {
    Response::setToJsonResponse();
    $controller = new DatasetController;
    $request = new Request();
    $formData = $request->all();

    return json_encode($controller->postAdd($formData));
  }
}
