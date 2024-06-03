<?php

namespace Core\NCMS\Admin\DB;

use \Exception;
use \PDO;
use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use Core\Route\Request;
use Core\Route\Response;
use HTML\BS\CARD;
use HTML\BS\FORM_CHECK_SWITCH;
use HTML\BS\FORM_SELECT;
use HTML\BS\ROW;
use HTML\A;
use HTML\BUTTON;
use HTML\DIV;
use HTML\FORM;
use HTML\H1;
use HTML\INPUT_NUMBER;
use HTML\INPUT_TEXT;
use HTML\INPUT_PASSWORD;
use HTML\LABEL;
use HTML\LI;
use HTML\NAV;
use HTML\OL;
use HTML\OPTION;
use HTML\P;
use HTML\SCRIPT;
use HTML\SPAN;

class ConfigDB extends AdminPage
{
  private const CONF_FILENAME = 'conf.jdb';
  private const CONF_PATH = DC::PSUPPORT . '/' . self::CONF_FILENAME;

  public function __construct()
  {
    parent::__construct();
    $this->setTitleText('Connection settings');
    $this->addJSBody(new SCRIPT('/core/NCMS/Admin/DB/configdb.js'));
  }

  private function getConfigString(): string
  {
    return file_get_contents(self::CONF_PATH);
  }

  private function getConfigData(): array
  {
    $decodedData = base64_decode($this->getConfigString());
    return json_decode($decodedData, true);
  }

  public function renderGet(): string
  {
    $form = new FORM('form', 'form-config-db', method: 'post');
    $row1 = new ROW([
      new DIV('col-lg-3 col-md-4 col-sm-6 mb-3', append: [
        new FORM_SELECT('connname', 'connname', 'Connection name', optionsList: [
          new OPTION('local', 'Localhost', selected: true),
          new OPTION('umbler', 'Umbler', selected: false),
        ])
      ]),
      new DIV('col-lg-3 col-md-4 col-sm-6 mb-3', append: [
        new LABEL('dbname', 'form-label', txt: 'DB Name'),
        new INPUT_TEXT('form-control', 'dbname', 'dbname', placeholder: 'Database name')
      ]),
      new DIV('col-lg-3 col-md-4 col-sm-6 mb-3', append: [
        new LABEL('host', 'form-label', txt: 'Host'),
        new INPUT_TEXT('form-control', 'host', 'host', placeholder: 'Hostname'),
        new SPAN('form-text', html: 'We recommend using the IP address instead of the domain name.')
      ]),
      new DIV('col-lg-3 col-md-4 col-sm-6 mb-3', append: [
        new LABEL('port', 'form-label', txt: 'Port'),
        new INPUT_NUMBER('form-control', 'port', 'port', min: 0, max: 65000, placeholder: 'Port number')
      ])
    ]);
    $row2 = new ROW([
      new DIV('col-lg-4 col-md-4 col-sm-6 mb-3', append: [
        new LABEL('us', 'form-label', txt: 'Username'),
        new INPUT_TEXT('form-control', 'us', 'us', placeholder: 'Username')
      ]),
      new DIV('col-lg-4 col-md-4 col-sm-6 mb-3', append: [
        new LABEL('pw', 'form-label', txt: 'Password'),
        new INPUT_PASSWORD('form-control', 'pw', 'pw', placeholder: 'Password')
      ]),
      new DIV('col-12 mb-3', append: [
        new FORM_CHECK_SWITCH('indupdateuspw', 'indupdateuspw', 'Update username and password')
      ])
    ]);
    $form->append([$row1->getTag(), $row2->getTag()]);
    $form->append(new DIV('col-12 d-flex justify-content-end', append: [
      new BUTTON('btn btn-warning me-2', 'btn-testconnection', 'Test connection', alt: 'Test this current data to connect to the database.'),
      new BUTTON('btn btn-primary', 'btn-submit', 'Save', type: 'submit')
    ]));

    $cardPrinc = new CARD('card-principal');
    $cardPrinc->getBody()->append(new H1('card-title', html: 'Database connection settings'));
    $cardPrinc->getBody()->append(new P(html: 'Here you can set all the necessary data to connect to the database.'));
    $cardPrinc->getBody()->append($form);

    $this->getBodyContainer()->append([
      new NAV(append: new OL('breadcrumb', appendList: [
        new LI('breadcrumb-item', append: new A('/ncms', 'NCMS')),
        new LI('breadcrumb-item', append: new A('/ncms/admin', 'Admin')),
        new LI('breadcrumb-item', append: new A('/ncms/admin/config', 'Config')),
        new LI('breadcrumb-item active', append: 'DB'),
      ])),
      $cardPrinc
    ]);

    $this->addJSBody((new SCRIPT())->innerHTML(<<<JS
    var tokenConfigDBData = '{$this->getConfigString()}';
    JS));

    return $this->render();
  }

  public function renderPost(): string
  {
    Response::setToJsonResponse();
    $request = new Request();
    $formData = $request->all();
    $configData = $this->getConfigData();
    if (!isset($configData[$formData['connname']])) {
      $configData[$formData['connname']] = [];
    }

    $configData[$formData['connname']]['host'] = $formData['host'];
    $configData[$formData['connname']]['port'] = $formData['port'];
    $configData[$formData['connname']]['dbname'] = $formData['dbname'];

    if (isset($formData['indupdateuspw']) && $formData['indupdateuspw'] == '1') {
      $configData[$formData['connname']]['us'] = $formData['us'];
      $configData[$formData['connname']]['pw'] = $formData['pw'];
    }

    $data = json_encode($configData);
    $encodedData = base64_encode($data);

    try {
      file_put_contents(self::CONF_PATH, $encodedData);
    } catch (Exception $e) {
      return json_encode([
        'status' => 'danger',
        'message' => 'Error on saving connection data.',
        'error' => $e->getMessage()
      ]);
    }

    return json_encode([
      'status' => 'success',
      'token' => $encodedData,
      'message' => 'Connection data saved successfully.'
    ]);
  }

  public function testConnection(): string
  {
    Response::setToJsonResponse();
    $request = new Request();
    $cred = $request->all();

    $dsn = "mysql:charset=utf8;host={$cred['host']};dbname={$cred['dbname']};port={$cred['port']}";

    try {
      new PDO($dsn, $cred['us'], $cred['pw']);
    } catch (Exception $e) {
      return json_encode([
        'status' => 'warning',
        'message' => 'Connection not possible.',
        'error' => $e->getMessage()
      ]);
    }

    return json_encode([
      'status' => 'success',
      'message' => 'Connection successfully made.'
    ]);
  }
}
