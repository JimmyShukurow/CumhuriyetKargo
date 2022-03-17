<?php

namespace App\View\Components;

use Illuminate\View\Component;

class button extends Component
{
    public $type;
    public $buttonText;

    public function __construct($type, $buttonText)
    {
        $this->type = $type;
        $this->buttonText = $buttonText;
    }


    public function render()
    {
        return view('components.button');
    }
}
