<?php

namespace App\Services;

use PHTML\HTML;

class CurriculoLayout
{
  public HTML $html;

  public function __toString()
  {
    return $this->html->render();
  }
}
