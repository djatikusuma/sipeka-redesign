<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FieldInput extends Component
{

    public $fieldName;
    public $labelName;
    public $placeholder;
    public $model;
    public $required;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($fieldName, $labelName, $placeholder, $model = null, $required = 'required')
    {
        //
        $this->fieldName = $fieldName;
        $this->labelName = $labelName;
        $this->placeholder = $placeholder;
        $this->model = $model;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.field-input');
    }
}
