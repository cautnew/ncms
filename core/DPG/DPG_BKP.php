<?php

namespace Core\DPG;

use Boot\Constants\Constant as C;
use Boot\Constants\DirConstant as DC;
use Core\Support\Session;
use Core\Support\InVar;
use DateTime;

use Cautnew\HTML\HTML;
use Cautnew\HTML\HEAD;
use Cautnew\HTML\BODY;
use Cautnew\HTML\TITLE;
use Cautnew\HTML\FOOTER;
use Cautnew\HTML\LINK;
use Cautnew\HTML\SCRIPT;

class DPG
{
  private Session $ses;
  private InVar $data;

  private HTML $html;
  private HEAD $head;
  private BODY $body;
  private TITLE $title;
  private FOOTER $footer;

  private string $pathView;
  private string $pgBaseName;
  private string $pgPathRoot;
  private string $radPathFile;
  private string $foldersForPage;
  private string $pgJsPathRoot;
  private string $pgCssPathRoot;
  private string $filePathDefaultFooter;
  private string $filePathDefaultHead;

  protected string $lang;
  protected string $charset;

  private bool $especificCSS;
  private bool $especificJS;
  private bool $indDynamicFooter;
  private bool $indShowFooter;

  protected string $titleFirstPart;
  protected string $titleSecondPart;

  protected array $headCSS;
  protected array $headJS;
  protected array $footerJS;

  private DateTime $timeStart;
  private DateTime $timeEnd;

  private ?string $codPg;

  public function __construct(?string $filename = null)
  {
    $this->setTimeStart();

    $this->setFileName($filename);
  }
  
  public function __set(string $name, $value)
  {
    $this->setData($name, $value);
  }
  
  public function __get(string $name)
  {
    return $this->getData($name);
  }
  
  public function setData(string $name, $value)
  {
    if (!isset($this->data)) {
      $this->data = new InVar();
    }

    $this->data->$name = $value;
  }
  
  public function getData(string $name)
  {
    if (!isset($this->data)) {
      $this->data = new InVar();
    }

    return $this->data->$name;
  }

  public function setSession(Session $ses): self
  {
    $this->ses = $ses;

    return $this;
  }

  /**
   * Return the current session element
   * @return Session
   */
  public function getSession(): Session
  {
    if (!isset($this->ses)) {
      $this->setSession(new Session());
    }
    return $this->ses;
  }

  public function setHtml(HTML $html): self
  {
    $this->html = $html;

    return $this;
  }

  public function getHtml(): HTML
  {
    if (!isset($this->html)) {
      $this->setHtml(new HTML());
    }

    return $this->html;
  }

  public function setHead(HEAD $head): self
  {
    $this->head = $head;

    return $this;
  }

  public function getHead(): HEAD
  {
    if (!isset($this->head)) {
      $this->setHead(new HEAD());
    }

    return $this->head;
  }

  public function setTitle(TITLE $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getTitle(): TITLE
  {
    if (!isset($this->title)) {
      $this->setTitle(new TITLE());
    }

    return $this->title;
  }

  public function setTitleFirstPart(?string $titleFirstPart = null): self
  {
    $this->titleFirstPart = $titleFirstPart;

    return $this;
  }

  public function getTitleFirstPart(): string
  {
    if (!isset($this->titleFirstPart)) {
      $this->setTitleFirstPart('');
    }

    return $this->titleFirstPart;
  }

  public function setTitleSecondPart(?string $titleSecondPart = null): self
  {
    $this->titleSecondPart = $titleSecondPart;

    return $this;
  }

  public function getTitleSecondPart(): string
  {
    if (!isset($this->titleSecondPart)) {
      $this->setTitleSecondPart('');
    }

    return $this->titleSecondPart;
  }

  public function setTitleTxt(string $titleTxt): self
  {
    return $this->setTitleFirstPart($titleTxt);
  }

  public function getTitleTxt(): string
  {
    return $this->getTitleFirstPart() . ' | ' . $this->getTitleSecondPart();
  }

  public function setBody(BODY $body): self
  {
    $this->body = $body;

    return $this;
  }

  public function getBody(): BODY
  {
    if (!isset($this->body)) {
      $this->setBody(new BODY());
    }

    return $this->body;
  }

  public function setFooter(FOOTER $footer): self
  {
    $this->footer = $footer;

    return $this;
  }

  public function getFooter(): FOOTER
  {
    if (!isset($this->footer)) {
      $this->setFooter(new FOOTER());
    }

    return $this->footer;
  }

  public function setTimeStart(?DateTime $timeStart = null): self
  {
    if ($timeStart === null) {
      $timeStart = new DateTime('now');
    }

    $this->timeStart = $timeStart;

    return $this;
  }

  public function getTimeStart(): DateTime
  {
    return $this->timeStart;
  }

  public function setTimeEnd(?DateTime $timeEnd = null): self
  {
    if ($timeEnd === null) {
      $timeEnd = new DateTime('now');
    }

    $this->timeEnd = $timeEnd;

    return $this;
  }

  public function getTimeEnd(): DateTime
  {
    return $this->timeEnd;
  }

  public function setPgBaseName(string $pgBaseName): self
  {
    $this->pgBaseName = $pgBaseName;

    return $this;
  }

  public function getPgBaseName(): string
  {
    return $this->pgBaseName;
  }

  public function setPgFilename(string $pgFileName): self
  {
    $this->pgFileName = $pgFileName;

    return $this;
  }

  public function getPgFilename(): string
  {
    return $this->pgFileName;
  }

  protected function setFoldersForPage(string $foldersForPage): self
  {
    $this->foldersForPage = $foldersForPage;

    return $this;
  }

  public function getFoldersForPage(): string
  {
    return $this->foldersForPage;
  }

  protected function setPgPathRoot(string $pgPathRoot): self
  {
    $this->pgPathRoot = $pgPathRoot;

    return $this;
  }

  public function getPgPathRoot(): string
  {
    return $this->pgPathRoot;
  }

  protected function setPgJsPathRoot(string $pgJsPathRoot): self
  {
    $this->pgJsPathRoot = $pgJsPathRoot;

    return $this;
  }

  public function getPgJsPathRoot(): string
  {
    return $this->pgJsPathRoot;
  }

  protected function setPgJsPathRefRoot(string $pgJsPathRefRoot): self
  {
    $this->pgJsPathRefRoot = $pgJsPathRefRoot;

    return $this;
  }

  public function getPgJsPathRefRoot(): string
  {
    return $this->pgJsPathRefRoot;
  }

  protected function setPgCssPathRoot(string $pgCssPathRoot): self
  {
    $this->pgCssPathRoot = $pgCssPathRoot;

    return $this;
  }

  public function getPgCssPathRoot(): string
  {
    return $this->pgCssPathRoot;
  }

  protected function setPgCssPathRefRoot(string $pgCssPathRefRoot): self
  {
    $this->pgCssPathRefRoot = $pgCssPathRefRoot;

    return $this;
  }

  public function getPgCssPathRefRoot(): string
  {
    return $this->pgCssPathRefRoot;
  }

  protected function setPathView(string $pathView): self
  {
    $this->pathView = $pathView;

    return $this;
  }

  public function getPathView(): string
  {
    return $this->pathView;
  }

  public function setRadPathFile(string $radPathFile): self
  {
    $this->radPathFile = $radPathFile;

    return $this;
  }

  public function getRadPathFile(): string
  {
    return $this->radPathFile;
  }

  public function setFilePathDefaultFooter(string $filePath): self
  {
    $this->filePathDefaultFooter = $filePath;
    return $this;
  }

  public function getFilePathDefaultFooter(): string
  {
    return $this->filePathDefaultFooter;
  }

  public function setFilePathDefaultHead(string $filePath): self
  {
    $this->filePathDefaultHead = $filePath;
    return $this;
  }

  public function getFilePathDefaultHead(): string
  {
    return $this->filePathDefaultHead;
  }

  protected function setFileName(?string $fileName = null): self
  {
    if (empty($fileName)) {
      $fileName = $_SERVER['PHP_SELF'];
    }

    $this->setPgBaseName(basename($fileName));
    $this->setPgFileName(str_replace('.php', '', $this->getPgBaseName()));

    $this->setFoldersForPage(str_replace('-', '/', $this->getPgFileName()));
    $this->setPgPathRoot(DC::PSOURCE . "/{$this->getFoldersForPage()}");

    $refPathToAuxIncludes = "/pgs/{$this->getFoldersForPage()}";
    $this->setPgJsPathRoot(DC::PJS . $refPathToAuxIncludes);
    $this->setPgJsPathRefRoot(DC::PRJS . $refPathToAuxIncludes);
    $this->setPgCssPathRoot(DC::PCSS . $refPathToAuxIncludes);
    $this->setPgCssPathRefRoot(DC::PRCSS . $refPathToAuxIncludes);

    $this->setRadPathFile("{$this->getPgPathRoot()}/{$this->getPgFileName()}");
    $this->setPathView("{$this->getRadPathFile()}-view.php");

    return $this;
  }

  public function setLanguage(string $lang = C::DEFAULT_LANGUAGE): self
  {
    if (!empty($lang)) {
      $this->lang = $lang;
    }

    return $this;
  }

  public function getLanguage(): string
  {
    if (!isset($this->lang)) {
      $this->setLanguage();
    }

    return $this->lang;
  }

  public function setCharset(string $charset = C::DEFAULT_CHARSET): self
  {
    if (!empty($charset)) {
      $this->charset = $charset;
    }

    return $this;
  }

  public function getCharset(): string
  {
    if (!isset($this->charset)) {
      $this->setCharset();
    }

    return $this->charset;
  }

  public function getHeadCSS(): array
  {
    if (!isset($this->headCSS)) {
      $this->headCSS = [];
    }

    return $this->headCSS;
  }

  public function getHeadJS(): array
  {
    if (!isset($this->headJS)) {
      $this->headJS = [];
    }
    return $this->headJS;
  }

  public function getHeadJSToHTML(): string
  {
    $html = '';
    foreach ($this->getHeadJS() as $src) {
      $script = new SCRIPT($src);
      $html .= $script->getHtml();
    }
    return $html;
  }

  public function getFooterJS(): array
  {
    if (!isset($this->footerJS)) {
      $this->footerJS = [];
    }

    return $this->footerJS;
  }

  public function getFooterJSToHTML(): string
  {
    $html = '';
    foreach ($this->getFooterJS() as $src) {
      $script = new SCRIPT($src);
      $html .= $script->getHtml();
    }
    return $html;
  }

  public function getExtJS(): string
  {
    return $this->extJS;
  }

  public function getExtCSS(): string
  {
    return $this->extCSS;
  }

  public function setEspecificCSS(bool $especificCSS = false): self
  {
    $this->especificCSS = $especificCSS;

    return $this;
  }

  public function getEspecificCSS(): bool
  {
    if (!isset($this->especificCSS)) {
      $this->setEspecificCSS();
    }

    return $this->especificCSS;
  }

  public function isEspecificCSS(): bool
  {
    return $this->getEspecificCSS();
  }

  public function setEspecificJS(bool $especificJS = false): self
  {
    $this->especificJS = $especificJS;

    return $this;
  }

  public function getEspecificJS(): bool
  {
    if (!isset($this->especificJS)) {
      $this->setEspecificJS();
    }

    return $this->especificJS;
  }

  public function isEspecificJS(): bool
  {
    return $this->getEspecificJS();
  }

  public function setIndDynamicFooter(bool $indDynamicFooter = false): self
  {
    $this->indDynamicFooter = $indDynamicFooter;

    return $this;
  }

  public function getIndDynamicFooter(): bool
  {
    if (!isset($this->indDynamicFooter)) {
      $this->setIndDynamicFooter();
    }

    return $this->indDynamicFooter;
  }

  public function setIndShowFooter(bool $indShowFooter = true): self
  {
    $this->indShowFooter = $indShowFooter;

    return $this;
  }

  public function getIndShowFooter(): bool
  {
    if (!isset($this->indShowFooter)) {
      $this->setIndShowFooter();
    }

    return $this->indShowFooter;
  }

  protected function includeHeadCSS(string $cssFile): bool
  {
    if (empty($cssFile)) {
      return false;
    }

    $cssFileName = "{$cssFile}.{$this->getExtCSS()}";

    $fullPathCssFile = DC::PCSS . "/$cssFileName";

    if (!file_exists($fullPathCssFile)) {
      return false;
    }

    $fileTimestamp = filectime($fullPathCssFile);
    $pathRefCssFile = DC::PRCSS . "/$cssFileName?v=$fileTimestamp";

    if (in_array($pathRefCssFile, $this->getHeadCSS())) {
      return true;
    }

    $this->headCSS[] = $pathRefCssFile;

    return true;
  }

  public function addCSS(array | string $listCss): self
  {
    if (gettype($listCss) == 'string') {
      $this->includeHeadCSS($listCss);
      return $this;
    }

    foreach ($listCss as $src) {
      $this->includeHeadCSS($src);
    }

    return $this;
  }

  protected function includeHeadJS(string $jsFile): bool
  {
    if (empty($jsFile)) {
      return false;
    }

    $fullPathCssFile = DC::PJS . "/{$jsFile}.{$this->getExtJS()}";

    if (!file_exists($fullPathCssFile)) {
      return false;
    }

    $fileTimestamp = filectime($fullPathCssFile);
    $jsFile = DC::PRJS . "/{$jsFile}.{$this->getExtJS()}?v={$fileTimestamp}";

    if (in_array($jsFile, $this->getHeadJS())) {
      return true;
    }

    $this->headJS[] = $jsFile;

    return true;
  }

  public function addHeadJS(array | string $listJs): self
  {
    if (gettype($listJs) == 'string') {
      $this->includeHeadJS($listJs);
      return $this;
    }

    foreach ($listJs as $src) {
      $this->includeHeadJS($src);
    }

    return $this;
  }

  protected function includeFooterJS(string $jsFile): bool
  {
    if (empty($jsFile)) {
      return false;
    }

    $fullPathCssFile = DC::PJS . "/{$jsFile}.{$this->getExtJS()}";

    if (!file_exists($fullPathCssFile)) {
      return false;
    }

    $fileTimestamp = filectime($fullPathCssFile);
    $jsFile = DC::PRJS . "/{$jsFile}.{$this->getExtJS()}?v={$fileTimestamp}";

    if (in_array($jsFile, $this->getFooterJS())) {
      return true;
    }

    $this->footerJS[] = $jsFile;

    return true;
  }

  public function addFooterJS(array | string $listJs): self
  {
    if (gettype($listJs) == 'string') {
      $this->includeFooterJS($listJs);
      return $this;
    }

    foreach ($listJs as $src) {
      $this->includeFooterJS($src);
    }

    return $this;
  }

  public function getValidPathForAux(string $aux): ?string
  {
    $path = "{$this->getRadPathFile()}-aux-{$aux}.php";

    if (!file_exists($path)) {
      return null;
    }

    return $path;
  }

  public function includeAux(string $aux): self
  {
    $path = $this->getValidPathForAux($aux);
    
    if ($path === null) {
      return $this;
    }
    
    include $path;

    return $this;
  }

  public function requireAux(string $aux): self
  {
    $path = $this->getValidPathForAux($aux);
    
    if ($path === null) {
      return $this;
    }

    require $path;

    return $this;
  }

  public function includeAuxFooterJS(string $auxJs): self
  {
    $src = "pgs/{$this->getFoldersForPage()}/{$this->getPgFileName()}-aux";
    $this->includeFooterJS("$src-$auxJs");

    return $this;
  }

  public function includeAuxHeadJS(string $auxJs): self
  {
    $src = "pgs/{$this->getFoldersForPage()}/{$this->getPgFileName()}-aux";
    $this->includeHeadJS("$src-$auxJs");

    return $this;
  }

  public function addAuxJS(array | string $listAuxJs): self
  {
    if (gettype($listAuxJs) == 'string') {
      return $this->includeAuxFooterJS($listAuxJs);
    }

    foreach ($listAuxJs as $auxJs) {
      $this->includeAuxFooterJS($auxJs);
    }

    return $this;
  }

  public function addAuxHeadJS(array | string $listAuxJs): self
  {
    if (gettype($listAuxJs) == 'string') {
      return $this->includeAuxHeadJS($listAuxJs);
    }

    foreach ($listAuxJs as $auxJs) {
      $this->includeAuxHeadJS($auxJs);
    }

    return $this;
  }

  public function addEspecificCSS(): self
  {
    if ($this->getEspecificCSS()) {
      return $this;
    }

    $src = "pgs/{$this->getFoldersForPage()}/{$this->getPgFileName()}";
    $this->addCSS($src);
    $this->setEspecificCSS(true);

    return $this;
  }

  public function addEspecificJS(): self
  {
    if ($this->getEspecificJS()) {
      return $this;
    }

    $src = "pgs/{$this->getFoldersForPage()}/{$this->getPgFileName()}";
    $this->addFooterJS($src);
    $this->getEspecificJS(true);

    return $this;
  }

  protected function prepareHeadCSS(): self
  {
    foreach ($this->getHeadCSS() as $src) {
      $this->getHead()->append(new LINK($src, 'stylesheet', 'text/css'));
    }

    return $this;
  }

  protected function prepareHeadJS(): self
  {
    foreach ($this->getHeadJS() as $src) {
      $this->getHead()->append(new SCRIPT($src));
    }

    return $this;
  }

  protected function isIE(): bool
  {
    $usag = $_SERVER['HTTP_USER_AGENT'];

    if (strpos($usag, 'MSIE') !== false) {
      return true;
    } elseif (strpos($usag, 'Trident') !== false) {
      return true;
    }

    return false;
  }

  public function isUserLoggedIn(): bool
  {
    return $this->getSession()->has('COD_USUARIO');
  }

  public function setCodPg(?string $codPg = null): self
  {
    $this->codPg = $codPg;

    return $this;
  }

  public function getCodPg(): ?string
  {
    if (!isset($this->codPg)) {
      $this->setCodPg();
    }

    return $this->codPg;
  }
}
