<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SuggestiveTextInput extends TextInput
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name,
        string $placeholder = null,
        string $value = null,
        string $type = 'text'
    ) {
        parent::__construct($value, $name, $placeholder, $type);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.suggestive-text-input');
    }
}
