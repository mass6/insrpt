<?php namespace Insight\Companies\Forms;
use Laracasts\Validation\FormValidator;
use Laracasts\Validation\FormValidationException;
use Log;
/**
 * Insight Client Management Portal:
 * Date: 7/28/14
 * Time: 3:32 PM
 */

class SupplierForm extends CompanyForm
{
    protected $rules = [
        'email'                 => 'required|email|unique:users',
        'name'                  => 'required|unique:companies',
        'notes'                 => 'max:1000',
        'address1_description'  => 'max:100|required_with:address1_body',
        'address1_body'         => 'max:500',
        'address2_description'  => 'max:100|required_with:address2_body',
        'address2_body'         => 'max:500',
        'address3_description'  => 'max:100|required_with:address3_body',
        'address3_body'         => 'max:500',
        'address4_description'  => 'max:100|required_with:address4_body',
        'address4_body'         => 'max:500',
        'first_name'            => 'required',
        'last_name'             => 'required'
    ];

} 