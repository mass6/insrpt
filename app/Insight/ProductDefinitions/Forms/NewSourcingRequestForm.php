<?php
namespace Insight\ProductDefinitions\Forms;
use Laracasts\Validation\FormValidator;
use Laracasts\Validation\FormValidationException;

class NewSourcingRequestForm extends FormValidator {
    /**
     * @var array
     */
    protected $rules = [
        'name'  => 'required|max:120',
        'category' => 'max:50',
        'uom' => 'max:25',
        'price' => 'numeric',
        'short_description' => 'required|max:1000',
        'image1' => 'required|image|max:1024|mimes:jpg,jpeg,png,gif,bmp,gif'
    ];
}