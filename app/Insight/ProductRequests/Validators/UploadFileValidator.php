<?php

namespace Insight\ProductRequests\Validators;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Insight\ProductRequests\Exceptions\ProductRequestImportFileException;
use Insight\ProductRequests\Exceptions\ProductRequestUploadFileException;
use Insight\Users\User;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Validates an uploaded file for creating a Product Request List and
 * multiple associated Product Requests.
 * Validates the spreadsheet structure, checks for duplicate products
 * within the spreadsheet, and validates the contents of each entry
 * against the established domain rules
 *
 * Class UploadFileValidator
 * @package Insight\Product
 */
class UploadFileValidator
{

    /**
     *
     */
    const NUM_REFS = 4;
    /**
     * @var array
     */
    protected $validationMessages = [
        'first_time_order_quantity.integer'  => 'The current price must be a whole number.',
        'volume_requested.integer'  => 'The current price must be a whole number.',
        'purchase_recurrence.in'    => "Accepted values for Purchase Recurrence are adhoc, monthly, or annually.",
        'current_price_currency.in' => "Currency code must adhere to the 3-digit ISO 4217 standard. See http://www.xe.com/iso4217.php for full list of currency codes."
    ];
    /**
     * @var string
     */
    private $primaryField = 'product_description';
    /**
     * @var array
     */
    private $baseColumns = [
        'product_description',
        'category',
        'uom',
        'first_time_order_quantity',
        'purchase_recurrence',
        'volume',
        'current_sku',
        'current_price',
        'current_price_currency',
        'current_supplier',
        'current_supplier_contact',
        'remarks',
    ];
    /**
     * @var bool
     */
    private $hasReferenceFields = false;
    /**
     * @var
     */
    private $referenceFields;
    /**
     * Expected columns in the upload file
     *
     * @var array
     */
    private $expectedColumns;
    /**
     * @var
     */
    private $columnHeadings;
    /**
     * @var
     */
    private $categories;
    /**
     * @var
     */
    private $input;
    /**
     * @var
     */
    private $workbook;
    /**
     * @var
     */
    private $spreadsheet;
    /**
     * @var User
     */
    private $user;
    /**
     * @var
     */
    private $settings;
    /**
     * @var ProductRequestValidationRule
     */
    private $productRequestValidationRule;

    /**
     * @param $input
     * @param User $user
     */
    public function __construct($input, User $user)
    {
        $this->input = $input;
        $this->user = $user;

        // Get the validation rules for Submitting new Product Requests
        $this->productRequestValidationRule = App::make('Insight\ProductRequests\Forms\ProductRequestValidationRule');
    }

    /**
     * Return the uploaded spreadsheet
     *
     * @return mixed
     */
    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    /**
     * Return the number of rows in the spreadsheet, which equates
     * to the number of requests to be created via uploaded.
     *
     * @return int
     */
    public function numRequests()
    {
        return count($this->spreadsheet);
    }

    /**
     * @return mixed
     */
    public function getReferenceFields()
    {
        return $this->referenceFields;
    }

    /**
     * Run all validation checks.
     * Will throw exceptions and redirect user back to
     * upload form showing the errors if any checks fail.
     *
     * @throws ProductRequestUploadFileException
     */
    public function validate()
    {
        $this->setFields();

        $this->loadSpreadsheet();

        $this->setReferenceFields();

        $this->setExpectedColumns();

        $this->setColumnHeadings();

        $this->validateColumnHeadings();

        $this->checkForMissingPrimaryField();

        $this->checkForDuplicateEntries();

        $this->validateSpreadsheetData();

        // if all checks pass, return true
        return true;
    }

    /**
     *
     */
    private function setFields()
    {
        $this->settings = $this->user->company->settings();
        $this->categories = $this->settings->get('product-requests.procurement-categories', ['Default']);
    }

    /**
     * Loads the uploaded file into a spreadsheet object
     */
    private function loadSpreadsheet()
    {
        // load the workbook and assigns to class property
        Excel::load($this->input['uploadfile'], function ($reader) {
            $this->workbook = $reader->get();
        });

        $this->spreadsheet = $this->workbook->first();
    }

    /**
     *
     */
    private function setReferenceFields()
    {
        $settings = $this->settings;

        $referenceFields = [];
        if ($settings->get('product-requests.references.enabled')) {
            for ($r = 1; $r <= self::NUM_REFS; $r ++) {
                $referenceFields[$r] = $settings->get("product-requests.reference{$r}");
                if (array_get($referenceFields[$r], 'enabled', false)) {
                    $this->hasReferenceFields = true;
                    $label = $referenceFields[$r]['label'];
                    $referenceFields[$r]['name'] = $label !== '' ? trim(strtolower(str_replace(' ', '_', $label))) : "reference{$r}";
                }
            }
        }

        $this->referenceFields = $referenceFields;
    }

    /**
     *
     */
    private function setExpectedColumns()
    {
        $this->expectedColumns = array_merge($this->baseColumns, $this->extractReferenceFieldColumns());
    }

    /**
     * @return array
     */
    private function extractReferenceFieldColumns()
    {
        $columns = [];
        foreach ($this->referenceFields as $number => $referenceField) {
            if (array_get($referenceField, 'enabled', false)) {
                $label = $referenceField['label'];
                $columns[] = $label !== '' ? trim(strtolower(str_replace(' ', '_', $label))) : "reference{$number}";
            }
        }

        return $columns;
    }

    /**
     *
     */
    private function setColumnHeadings()
    {
        $cellCollection = $this->spreadsheet->first();
        $columnHeadings = [];
        foreach ($cellCollection as $columnHeading => $value) {
            $columnHeadings[] = trim(strtolower($columnHeading));
        }

        $this->columnHeadings = $columnHeadings;
    }

    /**
     * Checks the the columns in the uploaded spreadsheet match
     * the expected columns
     *
     * @throws ProductRequestUploadFileException
     * @return mixed
     */
    private function validateColumnHeadings()
    {
        sort($this->columnHeadings);
        sort($this->expectedColumns);

        if ($this->columnHeadings !== $this->expectedColumns) {
            $errorMessage = 'Incorrect spreadsheet structure. Column names do not match expected format.';
            throw new ProductRequestUploadFileException($errorMessage, ['errorMessages' => $errorMessage, 'errorDetails' => null]);
        }

        return true;
    }

    /**
     * @throws ProductRequestUploadFileException
     */
    private function checkForMissingPrimaryField()
    {
        $collection = $this->spreadsheet;
        $uniquePrimaryFields = $collection->keyBy($this->primaryField);

        if ($uniquePrimaryFields->get('') !== null) {
            $errorMessage = 'One or more entries were found missing a ' . $this->primaryFieldToText() . '. Please make the necessary corrections and try again.';

            throw new ProductRequestUploadFileException($errorMessage, ['errorMessages' => $errorMessage, 'errorDetails' => "{$this->primaryFieldToText()} field missing."]);
        }
    }

    /**
     * @return string
     */
    private function primaryFieldToText()
    {
        return ucwords(str_replace('_', ' ', $this->primaryField));
    }

    /**
     * Checks the uploaded spreadsheet for any duplicate
     * product entries based on Primary Field column
     *
     * @return bool
     * @throws ProductRequestUploadFileException
     */
    private function checkForDuplicateEntries()
    {
        $collection = $this->spreadsheet;
        $uniquePrimaryFields = $collection->keyBy($this->primaryField);

        if ($collection->count() !== $uniquePrimaryFields->count()) {
            $duplicates = $this->getDuplicateEntries($collection);
            $errorMessage = 'One or more entries were found having the same ' . $this->primaryFieldToText() . '. Please make the necessary corrections and try again.';
            $errorDetails = $this->compileDuplicatesErrorMessage($duplicates);

            throw new ProductRequestUploadFileException($errorMessage, ['errorMessages' => $errorMessage, 'errorDetails' => $errorDetails]);
        }

        return true;
    }

    /**
     * If duplicates are found, return an array with the lines
     * that were duplicates.
     *
     * @param $collection
     * @return array
     */
    private function getDuplicateEntries($collection)
    {

        $duplicates = [];
        $toCompareWith = '';
        $entries = $collection->sortBy($this->primaryField);

        foreach ($entries as $index => $entry) {
            if (strtolower($entry->{$this->primaryField}) === strtolower($toCompareWith)) {
                $duplicates[] = $toCompareWith;
                continue;
            }
            $toCompareWith = $entry->{$this->primaryField};
        }

        return $duplicates;
    }

    /**
     * If duplicates are found, generate an error message listing
     * the duplicate items
     *
     * @param $duplicates
     * @return string
     */
    private function compileDuplicatesErrorMessage(Array $duplicates)
    {
        $errors = '';
        $duplicatesFound = [];

        foreach ($duplicates as $key => $value) {
            $duplicate = trim($value);
            if (!in_array($value, $duplicatesFound)) {
                $errors .= "<h5>Multiple entries found with a " . $this->primaryFieldToText() . " of \"{$duplicate}\"</h5>";
                $duplicatesFound[] = $duplicate;
            }
        }

        return $errors;
    }

    /**
     * Validate the data provided in the spreadsheet passes
     * the standard validation rules
     *
     * @return bool
     * @throws ProductRequestUploadFileException
     */
    private function validateSpreadsheetData()
    {
        $errorMessage = "There were errors validating the uploaded file.";
        $errorDetails = '';

        foreach ($this->spreadsheet as $row) {
            $validator = Validator::make(
                [
                    'product_description'      => $row->product_description,
                    'category'                 => strtolower($row->category),
                    'uom'                      => $row->uom,
                    'first_time_order_quantity' => $row->first_time_order_quantity,
                    'purchase_recurrence'      => $this->matchPurchaseRecurrenceCode($row->purchase_recurrence),
                    'volume_requested'         => $row->volume,
                    'sku'                      => $row->sku,
                    'current_price'            => $row->current_price,
                    'current_price_currency'   => $row->current_price_currency,
                    'current_supplier'         => $row->current_supplier,
                    'current_supplier_contact' => $row->current_supplier_contact,
                    'remarks'                  => $row->remarks,
                ] + $this->getReferenceFieldData($row),
                $this->validationRules($this->productRequestValidationRule->getValidationRules()),
                $this->getValidationMessages()
            );

            $errorDetails .= $this->compileValidationErrorMessages($validator, $row->product_description);
        }

        if ($errorDetails) {
            throw new ProductRequestUploadFileException($errorMessage, ['errorMessages' => $errorMessage, 'errorDetails' => $errorDetails]);
        }

        return true;
    }

    /**
     * @param $string
     * @return bool|string
     */
    private function matchPurchaseRecurrenceCode($string)
    {
        switch (strtolower($string)) {
            case 'ahc':
            case 'adhoc':
            case 'ad-hoc':
            case 'once':
            case 'onetime':
            case 'one-time':
            case 'one-off':
            case 'oneoff':
                return 'AHC';
                break;
            case 'mon':
            case 'monthly':
            case 'month':
            case 'months':
            case 'per-month':
            case 'per month':
            case 'every month':
            case 'every-month':
                return 'MON';
                break;
            case 'ann':
            case 'annually':
            case 'year':
            case 'yearly':
            case 'every year':
            case 'every-year':
            case 'per-annum':
            case 'per annum':
                return 'ANN';
                break;
            default:
                return false;
        }
    }

    /**
     * @param $rowData
     * @return array
     */
    private function getReferenceFieldData($rowData)
    {
        $referenceFieldData = [];
        if ($this->hasReferenceFields) {
            foreach ($this->referenceFields as $number => $referenceField) {
                if (array_get($referenceField, 'enabled', false)) {
                    $referenceFieldData[$referenceField['name']] = $rowData->{$referenceField['name']};
                }
            }
        }

        return $referenceFieldData;
    }

    /**
     * Unset or add any validation rules relevant for creation via upload
     *
     * @param $rules
     * @return mixed
     */
    private function validationRules($rules)
    {
        $rules['category'] = 'required|in:' . strtolower(implode(',', $this->categories));

        if ($this->hasReferenceFields) {
            foreach ($this->referenceFields as $number => $referenceField) {
                if (array_get($referenceField, 'enabled', false)) {
                    if (array_get($referenceField, 'required', false)) {
                        $rules[$referenceField['name']] = 'required|max:100';
                    } else {
                        $rules[$referenceField['name']] = 'max:100';
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    private function getValidationMessages()
    {
        return $this->validationMessages;
    }

    /**
     * If validation errors are found, generate an error message listing
     * the the validation errors on each line number.
     *
     * @param $validator
     * @param $productDescription
     * @return string
     * @internal param $errorMessages
     */
    private function compileValidationErrorMessages($validator, $productDescription)
    {
        $errorDetails = null;

        if ($validator->fails()) {
            $compiledErrorMessageString = '';
            foreach ($validator->messages()->all() as $errorMessage) {
                $compiledErrorMessageString .= $errorMessage . "<br/>";
            }
            $compiledErrorMessageString .= "<br/>";
            $errorDetails .= "<h5>{$productDescription}</h5>" . $compiledErrorMessageString;

            return $errorDetails;
        }

        return $errorDetails;
    }

}
 