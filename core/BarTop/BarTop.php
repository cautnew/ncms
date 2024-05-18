<?php

namespace Core\BarTop;

use HTML\A;
use HTML\BUTTON;
use HTML\DIV;
use HTML\H5;
use HTML\IMG;
use HTML\LI;
use HTML\NAV;
use HTML\UL;
use Core\Support\Session;
use DateTime;

class BarTop
{
  protected array $options = [];
  protected array $optionsRight = [];
  protected NAV $navBar;
  protected DIV $containerFluid;
  protected DIV $offCanvas;
  protected DIV $offCanvasHeader;
  protected DIV $offCanvasBody;
  protected BUTTON $btnToggle;
  protected BUTTON $btnClose;
  protected IMG $imgLogo;

  protected Session $ses;

  protected string $fileBarTopPath = '';
  protected string $pathToStaticFiles = PTMPFILES;
  protected string $backgroundColor = "#280034";
  protected string $srcImgLogo = '/shared/images/logo/logo-only.png';
  protected int $widthImgLogo = 30;
  protected int $heightImgLogo = 30;
  protected string $routeLogo = '/home';
  protected DateTime $momento;

  public function __construct(array $opts = [])
  {
    $this->momento = new DateTime('now');

    $this->startBarTopStaticFile($this->getSession()->COD_USUARIO);

    if ($opts !== [] || !$this->existsBarTopStaticFile()) {
      $this->setOptions($opts);
    }
  }

  public function setSession(Session $ses): self
  {
    $this->ses = $ses;

    return $this;
  }

  public function getSession(): Session
  {
    if (!isset($this->ses)) {
      $this->setSession(new Session());
    }
    return $this->ses;
  }

  private function startBarTopStaticFile(string $userId): void
  {
    $this->fileBarTopPath = $this->pathToStaticFiles . "/bartop-static-$userId.html";
  }

  private function existsBarTopStaticFile(): bool
  {
    return file_exists($this->fileBarTopPath);
  }

  private function saveBarTopStaticFile(): void
  {
    file_put_contents($this->fileBarTopPath, $this->getNavBar()->getHtml());
  }

  public function getOptions(): array
  {
    return $this->options;
  }

  public function setOptions(array $options = []): self
  {
    if ($options !== []) {
      $this->options = $options;
      return $this;
    }

    return $this;
  }

  public function addOption($option): void
  {
    $this->options[] = $option;
  }

  public function getOptionsRight(): array
  {
    return $this->optionsRight;
  }

  public function setOptionsRight(array $optionsRight): void
  {
    $this->optionsRight = $optionsRight;
  }

  public function addOptionRight($optionRight): void
  {
    $this->optionsRight[] = $optionRight;
  }

  protected function getADropDown($id, $txt, $url): A
  {
    return new A($url, $txt, 'dropdown-item', $id);
  }

  private function getHtmlOptions(): UL
  {
    $ulOptions = new UL('navbar-nav justify-content-end flex-grow-1 pe-3');

    foreach ($this->options as $idMenu => $infoMenu) {
      $idMenuUpper = strtoupper($idMenu);

      $divDropdown = (new DIV('dropdown-menu'))
      ->setAttr('aria-labelledby', $idMenuUpper);

      foreach ($infoMenu['itens'] as $idItnMenu => $itnMenu) {
        $divDropdown->add($this->getADropDown($idItnMenu, $itnMenu['nome'], $itnMenu['href']));
      }

      $aDropdown = (new A("#$idMenu", $infoMenu['nome'], 'nav-link dropdown-toggle', $idMenuUpper))
      ->setData('bs-toggle', 'dropdown');

      $liDropdown = (new LI('nav-item dropdown'))
      ->append($aDropdown)
      ->append($divDropdown);

      $ulOptions->append($liDropdown);
    }

    return $ulOptions;
  }

  private function getHtmlOptionsRight(): UL
  {
    $ulNavBarRight = new UL('navbar-nav flex-row d-print-none d-flex me-3');

    foreach ($this->getOptionsRight() as $option) {
      $ulNavBarRight->append($option);
    }

    return $ulNavBarRight;
  }

  public function render(): self
  {
    $aImgLogo = (new A($this->routeLogo))
    ->addClass('navbar-brand')
    ->setAttr('title', 'Syslefe')
    ->setAttr('alt', 'Home do Syslefe')
    ->append($this->getImgLogo());

    $this->getOffCanvasHeader()
    ->append(new H5('offcanvas-title', 'offcanvasNavbarLabel', 'Syslefe'))
    ->append($this->getBtnClose());

    $this->getOffCanvasBody()->append($this->getHtmlOptions());

    $this->getOffCanvas()
    ->append($this->getOffCanvasHeader())
    ->append($this->getOffCanvasBody());

    $this->getContainerFluid()
    ->append($aImgLogo)
    ->append(new DIV('d-flex', appendList: [
      $this->getHtmlOptionsRight(),
      $this->getBtnToggle()
    ]))
    ->append($this->getOffCanvas());

    $this->getNavBar()->setAttr('style', 'background-color: ' . $this->backgroundColor . '; z-index:10000;');
    $this->getNavBar()->append($this->getContainerFluid());

    $this->saveBarTopStaticFile();

    return $this;
  }

  public function setSrcImgLogo(string $srcImgLogo): self
  {
    $this->srcImgLogo = $srcImgLogo;

    return $this;
  }

  public function getSrcImgLogo(): string
  {
    if (!isset($this->srcImgLogo)) {
      $this->setSrcImgLogo('');
    }

    return $this->srcImgLogo;
  }

  public function setImgLogo(IMG $imgLogo): self
  {
    $this->imgLogo = $imgLogo;
    $this->imgLogo
    ->setSrc($this->getSrcImgLogo())
    ->setAlt('Logo do Syslefe no topo da página')
    ->addClass('d-inline-block align-top')
    ->setTitle('Syslefe')
    ->setAttr('height', $this->widthImgLogo)
    ->setAttr('width', $this->heightImgLogo);

    return $this;
  }

  public function getImgLogo(): IMG
  {
    if (!isset($this->imgLogo)) {
      $this->setImgLogo(new IMG($this->getSrcImgLogo()));
    }

    return $this->imgLogo;
  }

  public function setNavBar(NAV $navBar): self
  {
    $this->navBar = $navBar;
    $this->navBar->addClass('navbar fixed-top d-print-none');

    return $this;
  }

  public function getNavBar(): NAV
  {
    if (!isset($this->navBar)) {
      $this->setNavBar(new NAV());
    }

    return $this->navBar;
  }

  public function setContainerFluid(DIV $containerFluid): self
  {
    $this->containerFluid = $containerFluid;
    $this->containerFluid->addClass('container-fluid');

    return $this;
  }

  public function getContainerFluid(): DIV
  {
    if (!isset($this->containerFluid)) {
      $this->setContainerFluid(new DIV());
    }

    return $this->containerFluid;
  }

  public function setOffCanvasHeader(DIV $offCanvasHeader): self
  {
    $this->offCanvasHeader = $offCanvasHeader;
    $this->offCanvasHeader->addClass('offcanvas-header');

    return $this;
  }

  public function getOffCanvasHeader(): DIV
  {
    if (!isset($this->offCanvasHeader)) {
      $this->setOffCanvasHeader(new DIV());
    }

    return $this->offCanvasHeader;
  }

  public function setOffCanvasBody(DIV $offCanvasBody): self
  {
    $this->offCanvasBody = $offCanvasBody;
    $this->offCanvasBody->addClass('offcanvas-body');

    return $this;
  }

  public function getOffCanvasBody(): DIV
  {
    if (!isset($this->offCanvasBody)) {
      $this->setOffCanvasBody(new DIV());
    }

    return $this->offCanvasBody;
  }

  public function setOffCanvas(DIV $offCanvas): self
  {
    $this->offCanvas = $offCanvas;
    $this->offCanvas->setId('offcanvasNavbar');
    $this->offCanvas->setAria('labelledby', 'offcanvasNavbarLabel');
    $this->offCanvas->addClass('offcanvas offcanvas-end');
    $this->offCanvas->setAttr('tabindex', '-1');

    return $this;
  }

  public function getOffCanvas(): DIV
  {
    if (!isset($this->offCanvas)) {
      $this->setOffCanvas(new DIV());
    }

    return $this->offCanvas;
  }

  public function setBtnToggle(BUTTON $btnToggle): self
  {
    $this->btnToggle = $btnToggle;
    $this->btnToggle
    ->setAttr('id', 'btn-toggle-offcanvas')
    ->addClass('navbar-toggler border-0')
    ->setData('bs-toggle', 'offcanvas')
    ->setData('bs-target', '#offcanvasNavbar')
    ->setAria('controls', 'offcanvasNavBar')
    ->setAria('label', 'Toggle navigation');

    $this->btnToggle->append('<i class="fas fa-bars text-light"></i>');

    return $this;
  }

  public function getBtnToggle(): BUTTON
  {
    if (!isset($this->btnToggle)) {
      $this->setBtnToggle(new BUTTON());
    }

    return $this->btnToggle;
  }

  public function setBtnClose(BUTTON $btnClose): self
  {
    $this->btnClose = $btnClose;
    $this->btnClose->addClass('btn-close');
    $this->btnClose->setData('bs-dismiss', 'offcanvas');
    $this->btnClose->setAria('label', 'Close');

    return $this;
  }

  public function getBtnClose(): BUTTON
  {
    if (!isset($this->btnClose)) {
      $this->setBtnClose(new BUTTON());
    }

    return $this->btnClose;
  }

  public function getHtml(): string
  {
    // if (!$this->existsBarTopStaticFile()) {
    //   $this->render();
    // }
    $this->render();

    return file_get_contents($this->fileBarTopPath);
  }
}
