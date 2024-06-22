<?php

namespace Core\NCMS\Dataset;

use Boot\Constants\DirConstant as DC;
use Core\Dataset\DatasetModelSelect;
use Core\DPG\AdminPage;
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

class Dataset extends AdminPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/dataset/dataset';

  public function __construct()
  {
    $this->setTitleText('Datasets');
  }

  public function renderPageList(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE . '.chtml');
    }

    $this->setBodyTitleText('Datasets');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('Dataset', true)
    ]);

    $tableList = new TABLE('table table-striped table-responsive table-hover', 'table-dataset-list');
    $tableList->append([
      new THEAD('table-dark', append: new TR(appendList: [
        new TH('text-center', html: 'ID'),
        new TH('text-center', html: 'Name'),
        new TH('text-center', html: 'Fields'),
        new TH('text-center', html: 'Active'),
        new TH('text-center', html: 'Description'),
        new TH('text-center', html: 'Actions'),
      ])),
      new TBODY()
    ]);

    $this->getCardPrinc()->getBody()->append([
      new P(html: 'Here you can check all datasets registered in the system.'),
      new DIV('col-12 d-flex justify-content-end mb-2', append: [
        new A('/ncms/dataset/add', class: 'btn btn-success', append: [new ICON_PLUS(), ' New Dataset'])
      ]),
      new DIV(append: $tableList)
    ]);

    parent::__construct();

    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/loaddatasetlist.js'));

    return $this->render(self::PATH_CACHE . '.chtml');
  }

  private function getFormDataset(): FORM
  {
    $form = new FORM('form', 'form-add-dataset', '/ncms/dataset/add', 'POST');

    $form->append([
      new ROW([
        new COL('mb-2', size: 12, onMd: 8, append: [
          new P('form-label', html: 'Name'),
          new INPUT('text', 'form-control', 'var_name', 'var_name', required: true)
        ]),
        new COL('mb-2', size: 12, onMd: 8, append: [
          new P('form-label', html: 'Controller'),
          new INPUT('text', 'form-control', 'var_controller', 'var_controller', required: true)
        ]),
        new COL('mb-2', size: 12, onMd: 8, append: [
          new P('form-label', html: 'Description'),
          new TEXTAREA('form-control', 'txt_description', 'txt_description')
        ]),
      ]),
      new ROW([
        new COL('mb-2', size: 6, append: [
          new DIV('form-check form-switch', append: [
            new INPUT('checkbox', 'form-check-input', 'bol_active', 'bol_active', '1'),
            new LABEL('bol_active', 'form-check-label', 'bol_active', 'Active')
          ])
        ])
      ]),
      new HR(),
      new H4(html: 'Fields'),
      new DIV('d-flex justify-content-end mb-2', append: [
        new BUTTON('btn btn-success', 'btn-add-field', type: 'button', append: [
          new ICON_PLUS(),
          ' Add field'
        ])
      ]),
      new DIV('table-responsive', append: new TABLE('table table-striped table-hover', 'table-fields', append: [
        new THEAD('table-dark', append: new TR(appendList: [
          new TH('text-center', html: 'Name'),
          new TH('text-center', html: 'Type'),
          new TH('text-center', html: 'References'),
          new TH('text-center', html: 'Description'),
          new TH('text-center', html: 'Actions'),
        ])),
        new TBODY()
      ])),
      new SPAN('text-muted', 'num-fields', "Number of fields: 0."),
      new P(html: 'A dataset must have at least one field.'),
      new P(html: 'The ID field of a dataset will always be called "var_cid". If you need another dataset ID consider creating an alias for this purpose.'),
      new P(html: 'We added 6 other fields to help to control of the dataset:'),
      new UL(appendList: [
        new LI(html: 'dtm_created: A datetime field to store the creation date of the dataset.'),
        new LI(html: 'dtm_updated: A datetime field to store the last update date of the dataset.'),
        new LI(html: 'dtm_expired: A datetime field to store the expiration date of the dataset.'),
        new LI(html: 'var_user_created: A varchar field to store the user that created the dataset.'),
        new LI(html: 'var_user_updated: A varchar field to store the user that updated the dataset.'),
        new LI(html: 'var_user_expired: A varchar field to store the user that expired the dataset.'),
      ]),
      new P(html: 'In this case all those names for fields (var_cid, dtm_created, dtm_updated, dtm_expired, var_user_created, var_user_updated, var_user_expired) are reserved and cannot be used for other purposes.'),
      new HR(),
      new H4(html: 'Triggers'),
      new ROW([
        new COL('d-flex justify-content-end', size: 12, append: [
          new BUTTON('btn btn-primary', 'btn-submit', value: 'save', type: 'submit', append: [
            new ICON_FLOPPY_DISK(),
            ' Save'
          ])
        ])
      ])
    ]);

    return $form;
  }

  public function renderPageAdd(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE . '-add.chtml');
    }

    $this->setBodyTitleText('Add Dataset');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('Dataset', false, '/ncms/dataset'),
      new BREADCRUMB_ITEM('Add', true)
    ]);

    $this->getCardPrinc()->getBody()->append([
      new P(html: 'Fill up the form below to add a new dataset. Pay attention to the fields marked with <strong>*</strong>.'),
      $this->getFormDataset()
    ]);

    parent::__construct();

    $this->addJSBody(new SCRIPT('/core/NCMS/Dataset/datasetsaddfeatures.js'));

    return $this->render(self::PATH_CACHE . '-add.chtml');
  }

  public function renderPageEdit($id = null): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE . '-edit.chtml');
    }

    $this->setBodyTitleText('Edit Dataset');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('Dataset', false, '/ncms/dataset'),
      new BREADCRUMB_ITEM('Edit', true)
    ]);

    parent::__construct();

    return $this->render(self::PATH_CACHE . '-edit.chtml');
  }

  public function renderPageView($id = null): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE . '-view.chtml');
    }

    $this->setBodyTitleText('View Dataset');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('Dataset', false, '/ncms/dataset'),
      new BREADCRUMB_ITEM('View', true)
    ]);

    parent::__construct();

    return $this->render(self::PATH_CACHE . '-view.chtml');
  }

  public function renderPageDelete($id = null): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE . '-delete.chtml');
    }

    $this->setBodyTitleText('Delete Dataset');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('Dataset', false, '/ncms/dataset'),
      new BREADCRUMB_ITEM('Delete', true)
    ]);

    parent::__construct();

    return $this->render(self::PATH_CACHE . '-delete.chtml');
  }

  public function renderList(): string
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

  public function datasetExistsByName(string $name = ''): bool
  {
    Response::setToJsonResponse();
    return false;
  }
}
