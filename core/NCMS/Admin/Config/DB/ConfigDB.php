<?php

namespace Core\NCMS\Admin\Config\DB;

use \Exception;
use \PDO;
use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use Core\Route\Request;
use Core\Route\Response;
use HTML\BS\BREADCRUMB_ITEM;
use HTML\BS\CARD;
use HTML\BS\FORM_CHECK_SWITCH;
use HTML\BS\FORM_SELECT;
use HTML\BS\ROW;
use HTML\A;
use HTML\BS\COL;
use HTML\BUTTON;
use HTML\DIV;
use HTML\FORM;
use HTML\H1;
use HTML\INPUT_NUMBER;
use HTML\INPUT_TEXT;
use HTML\INPUT_PASSWORD;
use HTML\LABEL;
use HTML\LI;
use HTML\OPTION;
use HTML\P;
use HTML\SCRIPT;
use HTML\SPAN;

class ConfigDB extends AdminPage
{
  private const CONF_FILENAME = 'conf.jdb';
  private const CONF_PATH = DC::PSUPPORT . '/' . self::CONF_FILENAME;
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/config/db/configdb.chtml';

  public function __construct()
  {
    $this->setTitleText('Connection settings');
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
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE);
    }

    $this->setBodyTitleText('Database connection settings');
    $this->addJSBody(new SCRIPT('/core/NCMS/Admin/Config/DB/configdb.js'));

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('Admin', false, '/ncms/admin'),
      new BREADCRUMB_ITEM('Config', false, '/ncms/admin/config'),
      new BREADCRUMB_ITEM('DB', true)
    ]);

    $form = new FORM('form', 'form-config-db', method: 'post');
    $row1 = new ROW([
      (new COL('mb-3', onLg: 3, onMd: 6, onSm: 6, append: [
        new FORM_SELECT('connname', 'connname', 'Connection name', optionsList: [
          new OPTION('local', 'Localhost', selected: true),
          new OPTION('umbler', 'Umbler', selected: false),
        ])
      ])),
      (new COL('mb-3', onLg: 3, onMd: 6, onSm: 6, append: [
        new LABEL('dbname', 'form-label', txt: 'DB Name'),
        new INPUT_TEXT('form-control', 'dbname', 'dbname', placeholder: 'Database name')
      ])),
      (new COL('mb-3', onLg: 3, onMd: 6, onSm: 6, append: [
        new LABEL('host', 'form-label', txt: 'Host'),
        new INPUT_TEXT('form-control', 'host', 'host', placeholder: 'Hostname'),
        new SPAN('form-text', html: 'We recommend using the IP address instead of the domain name.')
      ])),
      (new COL('mb-3', onLg: 3, onMd: 6, onSm: 6, append: [
        new LABEL('port', 'form-label', txt: 'Port'),
        new INPUT_NUMBER('form-control', 'port', 'port', min: 0, max: 65000, placeholder: 'Port number')
      ]))
    ]);
    $row2 = new ROW([
      (new COL('mb-3', onLg: 4, onMd: 4, onSm: 6, append: [
        new LABEL('us', 'form-label', txt: 'Username'),
        new INPUT_TEXT('form-control', 'us', 'us', placeholder: 'Username')
      ])),
      (new COL('mb-3', onLg: 4, onMd: 4, onSm: 6, append: [
        new LABEL('pw', 'form-label', txt: 'Password'),
        new INPUT_PASSWORD('form-control', 'pw', 'pw', placeholder: 'Password')
      ])),
      (new COL('mb-3', 12, append: [
        new FORM_CHECK_SWITCH('indupdateuspw', 'indupdateuspw', 'Update username and password')
      ]))
    ]);
    $form->append([$row1->getTag(), $row2->getTag()]);
    $form->append(new COL('d-flex justify-content-end align-items-middle', 12, append: [
      new DIV('d-flex align-items-center me-2', 'status-txt'),
      new BUTTON('btn btn-warning me-2', 'btn-testconnection', 'Test connection', alt: 'Test this current data to connect to the database.'),
      new BUTTON('btn btn-primary', 'btn-submit', 'Save', type: 'submit')
    ]));

    $this->getCardPrinc()->getBody()->append(new P(html: 'Here you can set all the necessary data to connect to the database.'));
    $this->getCardPrinc()->getBody()->append($form);

    $this->addJSBody((new SCRIPT())->innerHTML(<<<JS
    var tokenConfigDBData = '{$this->getConfigString()}';
    JS));

    parent::__construct();

    return $this->render(self::PATH_CACHE);
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
      'message' => 'Connection successfully stabilished.'
    ]);
  }
}
