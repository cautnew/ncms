<?php

namespace App\Templates\PurinaEU;

use CN\PHTML\Templates\HTML5;

class HomePage
{
    public function __tostring()
    {
        return $this->render();
    }

    public function render()
    {
        $html5 = new HTML5();
        return 'Foi.';
    }
}
