<?php

namespace Core\NCMS\Dataset;

use HTML\A;
use HTML\BS\COL;
use HTML\BS\ROW;
use HTML\FA\ICON_DEFEDIT;
use HTML\FA\ICON_FLOPPY_DISK;
use HTML\FA\ICON_PEN_TO_SQUARE;
use HTML\FA\ICON_PENTOSQUARE;
use HTML\FA\ICON_PLUS;
use HTML\DIV;
use HTML\BUTTON;
use HTML\FORM;
use HTML\H4;
use HTML\HR;
use HTML\INPUT;
use HTML\LABEL;
use HTML\LI;
use HTML\P;
use HTML\SPAN;
use HTML\TABLE;
use HTML\TBODY;
use HTML\TEXTAREA;
use HTML\TH;
use HTML\THEAD;
use HTML\TR;
use HTML\UL;

class DatasetForm
{
  public static function getFormDatasetCreation(): FORM
  {
    $form = new FORM('form', "form-add-dataset", "/ncms/datasets/add", 'POST');

    $form->append([
      new ROW([
//        new COL('mb-2', size: 12, onMd: 8, onLg: 7, append: [
//          new LABEL('var_controller', 'form-label', txt: 'Controller'),
//          new INPUT('text', 'form-control', 'var_controller', 'var_controller', required: true)
//        ]),
        new COL('mb-2', size: 12, onMd: 8, onLg: 7, append: [
          new LABEL('var_name', 'form-label', txt: 'Name'),
          new INPUT('text', 'form-control', 'var_name', 'var_name', required: true)
        ]),
        new COL('mb-2', size: 12, onMd: 8, onLg: 7, append: [
          new LABEL('var_alias', 'form-label', txt: 'Alias'),
          new INPUT('text', 'form-control', 'var_alias', 'var_alias', required: true)
        ]),
        new COL('mb-2', size: 12, onMd: 8, onLg: 7, append: [
          new LABEL('txt_description', 'form-label', txt: 'Description'),
          new TEXTAREA('form-control', 'txt_description', 'txt_description')
        ]),
      ]),
      new ROW([
        new COL('mb-2', size: 12, append: [
          new DIV('form-check form-switch', append: [
            new INPUT('checkbox', 'form-check-input', 'bol_enabled', 'bol_enabled', '1'),
            new LABEL('bol_enabled', 'form-check-label', 'bol_enabled', 'Active')
          ])
        ]),
        new COL('mb-2', size: 12, append: [
          new DIV('form-check form-switch', append: [
            new INPUT('checkbox', 'form-check-input', 'bol_admin', 'bol_admin', '1'),
            new LABEL('bol_admin', 'form-check-label', 'bol_admin', 'Restricted for admins only'),
            new COL(size: 12, append: new SPAN('form-text', html: 'Can be modified only by admins. If you need to change the fields '))
          ])
        ]),
        new COL('mb-2', size: 12, append: [
          new DIV('form-check form-switch', append: [
            new INPUT('checkbox', 'form-check-input', 'bol_system', 'bol_system', '1'),
            new LABEL('bol_system', 'form-check-label', 'bol_system', 'System dataset'),
            new COL(size: 12, append: new SPAN('form-text', html: 'Cannot be deleted or edited from UI, but can insert new values. If you need to modify the items you need to implement an update to the core.'))
          ])
        ])
      ]),
      new HR(),
      new H4(html: 'Fields'),
      new DIV('d-flex justify-content-end mb-2', append: [
        new BUTTON('btn btn-success', 'btn-add-field', type: 'button', title: 'Add a field', append: [
          new ICON_PLUS(),
          ' Add field'
        ])
      ]),
      new DIV('table-responsive', append: new TABLE('table table-striped table-hover', 'table-fields', append: [
        new THEAD('table-dark', append: new TR(appendList: [
          new TH('text-center', html: 'Name'),
          new TH('text-center', html: 'Type'),
          new TH('text-center', html: 'Reference'),
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
      new P(html: 'There are <strong>two</strong> triggers created by default for each dataset:'),
      new UL(appendList: [
        new LI(html: 'ncms_tb_<dataset-name>_before_insert (before insert): This trigger is called before a new record is inserted in the dataset and sets the values for creation date, also sets null for update and expiration dates.'),
        new LI(html: 'ncms_tb_<dataset-name>_before_update (before update): This trigger is called before a record is updated in the dataset and sets the value for updating date, also removes any change for the field dat_created.'),
      ]),
      new P(html: ''),
      new ROW([
        new COL('d-flex justify-content-end', size: 12, append: [
          new BUTTON('btn btn-primary btn-submit', value: 'save', type: 'submit', append: [
            new ICON_FLOPPY_DISK(),
            ' Create dataset'
          ])
        ])
      ])
    ]);

    return $form;
  }

  public static function getFormDatasetEdit(string $id): FORM
  {
    $form = new FORM('form', "form-edit-dataset", "/ncms/datasets/edit", 'POST');

    $form->append([
      new ROW([
//        new COL('mb-2', size: 12, onMd: 8, onLg: 7, append: [
//          new LABEL('var_controller', 'form-label', txt: 'Controller'),
//          new INPUT('text', 'form-control', 'var_controller', 'var_controller', required: true)
//        ]),
        new COL('mb-2', size: 12, onMd: 8, onLg: 7, append: [
          new LABEL('var_name', 'form-label', txt: 'Name'),
          new INPUT('text', 'form-control', 'var_name', 'var_name', required: true)
        ]),
        new COL('mb-2', size: 12, onMd: 8, onLg: 7, append: [
          new LABEL('var_alias', 'form-label', txt: 'Alias'),
          new INPUT('text', 'form-control', 'var_alias', 'var_alias', required: true)
        ]),
        new COL('mb-2', size: 12, onMd: 8, onLg: 7, append: [
          new LABEL('txt_description', 'form-label', txt: 'Description'),
          new TEXTAREA('form-control', 'txt_description', 'txt_description')
        ]),
      ]),
      new ROW([
        new COL('mb-2', size: 12, append: [
          new DIV('form-check form-switch', append: [
            new INPUT('checkbox', 'form-check-input', 'bol_enabled', 'bol_enabled', '1'),
            new LABEL('bol_enabled', 'form-check-label', 'bol_enabled', 'Active')
          ])
        ]),
        new COL('mb-2', size: 12, append: [
          new DIV('form-check form-switch', append: [
            new INPUT('checkbox', 'form-check-input', 'bol_admin', 'bol_admin', '1'),
            new LABEL('bol_admin', 'form-check-label', 'bol_admin', 'Restricted for admins only'),
            new COL(size: 12, append: new SPAN('form-text', html: 'Can be modified only by admins. If you need to change the fields '))
          ])
        ]),
        new COL('mb-2', size: 12, append: [
          new DIV('form-check form-switch', append: [
            new INPUT('checkbox', 'form-check-input', 'bol_system', 'bol_system', '1'),
            new LABEL('bol_system', 'form-check-label', 'bol_system', 'System dataset'),
            new COL(size: 12, append: new SPAN('form-text', html: 'Cannot be deleted or edited from UI, but can insert new values. If you need to modify the items you need to implement an update to the core.'))
          ])
        ])
      ]),
      new ROW([
        new COL('d-flex justify-content-end', size: 12, append: [
          new BUTTON('btn btn-primary btn-submit', value: 'save', type: 'submit', title: 'Save this content', append: [
            new ICON_FLOPPY_DISK(),
            ' Save'
          ])
        ])
      ]),
      new HR(),
      new H4(html: 'Fields'),
      new DIV('d-flex justify-content-end mb-2', append: [
        new A("/ncms/datasets/fields/{$id}/edit", class: 'btn btn-light', title: 'Add a field', append: [
          new ICON_PEN_TO_SQUARE(),
          ' Modify fields'
        ])
      ]),
      new DIV('table-responsive', append: new TABLE('table table-striped table-hover', 'table-fields', append: [
        new THEAD('table-dark', append: new TR(appendList: [
          new TH('text-center', html: 'Name'),
          new TH('text-center', html: 'Type'),
          new TH('text-center', html: 'Reference'),
          new TH('text-center', html: 'Description'),
          new TH('text-center', html: 'Actions'),
        ])),
        new TBODY()
      ])),
      new SPAN('text-muted', 'num-fields', "Number of fields: 0."),
      new P(html: 'A dataset must have at least one field.'),
      new HR(),
      new H4(html: 'Triggers'),
      new P(html: 'There are <strong>two</strong> triggers created by default for each dataset:'),
      new UL(appendList: [
        new LI(html: 'ncms_tb_<dataset-name>_before_insert (before insert): This trigger is called before a new record is inserted in the dataset and sets the values for creation date, also sets null for update and expiration dates.'),
        new LI(html: 'ncms_tb_<dataset-name>_before_update (before update): This trigger is called before a record is updated in the dataset and sets the value for updating date, also removes any change for the field dat_created.'),
      ]),
      new P(html: ''),
    ]);

    return $form;
  }

  public static function getFormDataset(string $event = 'add', string $id = ""): FORM
  {
    if ($event === 'edit') {
      if (empty($id)) {
        throw new Exception("Parameter \$id is missing");
      }

      return self::getFormDatasetEdit($id);
    }

    return self::getFormDatasetCreation();
  }
}
