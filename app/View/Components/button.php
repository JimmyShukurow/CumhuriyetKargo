<?php

namespace App\View\Components;

use Illuminate\View\Component;

class button extends Component
{
    public $type;
    public $buttonText;
    public $id;

    public function __construct($type, $buttonText, $id)
    {
        $this->type = $type;
        $this->buttonText = $buttonText;
        $this->id = $id;
    }


    public function render()
    {
        return view('components.button');
    }
}
