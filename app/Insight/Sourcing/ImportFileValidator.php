<?php

namespace Insight\Sourcing;


use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Insight\Sourcing\Exceptions\SourcingRequestImportFileException;
use Insight\Users\User;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Validates an imported file for creating multiple Sourcing Requests
 * Validates the spreadsheet structure, checks for duplicate products
 * within the spreadsheet, and validates the contents of each entry
 * against the established domain rules
 *
 * Class ImportFileValidator
 * @package Insight\Sourcing
 */
class ImportFileValidator
{

    /**
     * Expected columns in the import file
     *
     * @var array
     */
    private $importColumns = [
        'line_number',
        'customer_sku',
        'customer_product_description',
        'customer_price',
        'customer_price_currency',
        'customer_uom'
    ];

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
     * @var SourcingRequestValidationRule
     */
    private $sourcingRequestValidationRule;

    /**
     * @param $input
     * @param User $user
     */
    public function __construct($input, User $user)
    {
        $this->input = $input;
        $this->user = $user;

        // Get the validation rules for new Sourcing Requests
        $this->sourcingRequestValidationRule = App::make('Insight\Sourcing\Forms\SourcingRequestValidationRule');
    }

    /**
     * Run all validation checks.
     * Will throw exceptions and redirect user back to
     * upload form showing the errors if any checks fail.
     *
     * @throws SourcingRequestImportFileException
     */
    public function validate()
    {
        $this->loadSpreadsheet();

        $this->validateSpreadsheetStructure();

        $this->checkForDuplicateEntries();

        $this->validateSpreadsheetData();

        // if all checks pass, return true
        return true;
    }

    /**
     * Loads the imported file into a spreadsheet object
     */
    private function loadSpreadsheet()
    {
        // load the workbook and assigns to class property
        Excel::load($this->input['importfile'], function ($reader) {
            $this->workbook = $reader->get();
        });
    }

    /**
     * Checks the the columns in the imported spreadsheet match
     * the expected columns
     *
     * @throws SourcingRequestImportFileException
     * @return mixed
     */
    private function validateSpreadsheetStructure()
    {
        $this->spreadsheet = $this->workbook->first();
        $cellCollection = $this->spreadsheet->first();

        // Verify column heading are correct
        $columnHeadings = [];
        foreach ($cellCollection as $columnHeading => $value) {
            $columnHeadings[] = trim(strtolower($columnHeading));
        }

        if ($columnHeadings !== $this->importColumns) {
            $errorMessage = 'Incorrect spreadsheet structure. Column names do not match expected format.';
            throw new SourcingRequestImportFileException($errorMessage, ['errorMessages' => $errorMessage, 'errorDetails' => null]);
        }

        return true;
    }

    /**
     * Checks the imported spreadsheet for any duplicate
     * product entries based on Customer Product Description column
     *
     * @return bool
     * @throws SourcingRequestImportFileException
     */
    private function checkForDuplicateEntries()
    {
        $collection = $this->spreadsheet;
        $uniqueProductDescriptions = $collection->keyBy('customer_product_description');

        if ($collection->count() !== $uniqueProductDescriptions->count()) {
            $duplicates = $this->getDuplicateEntries($collection);
            $errorMessage = 'Duplicate entries were found having the same "Customer Product Description".<br/>Please remove any duplicates entries from the file and try again.';
            $errorDetails = $this->compileDuplicatesErrorMessage($duplicates);

            throw new SourcingRequestImportFileException($errorMessage, ['errorMessages' => $errorMessage, 'errorDetails' => $errorDetails]);
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
        $entries = $collection->sortBy('customer_product_description');

        $duplicates = [];
        $productToCompareWith = '';

        foreach ($entries as $index => $entry) {
            if (strtolower($entry->customer_product_description) === strtolower($productToCompareWith)) {
                $duplicates[$productToCompareWith][] = $entry->line_number;
                continue;
            }
            $productToCompareWith = $entry->customer_product_description;
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

        foreach ($duplicates as $item => $duplicates) {
            $item = trim($item);
            $errors .= "<h5>" . !$item === '' ? $item : '<em>Blank entry</em>' . "</h5>";

            $errors .= "<ul>";
            foreach ($duplicates as $duplicate) {
                $errors .= "<li>Duplicate entry found on line number {$duplicate}</li>";
            }
            $errors .= "</ul>";

        }

        return $errors;
    }

    /**
     * Validate the data provided in the spreadsheet passes
     * the standard validation rules
     *
     * @return bool
     * @throws SourcingRequestImportFileException
     */
    private function validateSpreadsheetData()
    {
        $errorMessage = "There were errors validating the uploaded file.";
        $errorDetails = '';

        foreach ($this->spreadsheet as $row) {
            $validator = Validator::make(
                [
                    'customer_sku'                 => $row->customer_sku,
                    'customer_product_description' => $row->customer_product_description,
                    'customer_price'               => $row->customer_price,
                    'customer_price_currency'      => $row->customer_price_currency,
                    'customer_uom'                 => $row->customer_uom,
                ],
                $this->validationRules($this->sourcingRequestValidationRule->getValidationRules())
            );

            $errorDetails .= $this->compileValidationErrorMessages($validator, $row->line_number);
        }

        if ($errorDetails) {
            throw new SourcingRequestImportFileException($errorMessage, ['errorMessages' => $errorMessage, 'errorDetails' => $errorDetails]);
        }

        return true;
    }

    /**
     * Unset any validation rules not relevant for creation via import
     *
     * @param $standardRules
     * @return mixed
     */
    private function validationRules($standardRules)
    {
        $standardRules['customer_sku'] = 'max:120|unique:sourcing_requests,customer_sku,NULL,id,customer_id,' . $this->input['customer_id'];
        unset($standardRules['customer_id']);
        unset($standardRules['received_on']);
        unset($standardRules['status']);


        return $standardRules;
    }

    /**
     * If validation errors are found, generate an error message listing
     * the the validation errors on each line number.
     *
     * @param $validator
     * @param $rowNum
     * @internal param $errorMessages
     * @return string
     */
    private function compileValidationErrorMessages($validator, $rowNum)
    {
        $errorDetails = null;

        if ($validator->fails()) {
            $compiledErrorMessageString = '';
            foreach ($validator->messages()->all() as $errorMessage) {
                $compiledErrorMessageString .= $errorMessage . "<br/>";
            }
            $compiledErrorMessageString .= "<br/>";
            $errorDetails .= "Line Number {$rowNum}:<br/>" . $compiledErrorMessageString;

            return $errorDetails;
        }

        return $errorDetails;
    }

    /**
     * Return the imported spreadsheet
     *
     * @return mixed
     */
    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    /**
     * Return the number of rows in the spreadsheet, which equates
     * to the number of requests to be created via import.
     *
     * @return int
     */
    public function numRequests()
    {
        return count($this->spreadsheet);
    }
    
}
 