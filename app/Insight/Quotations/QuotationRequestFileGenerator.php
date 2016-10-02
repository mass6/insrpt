<?php

namespace Insight\Quotations;

use Maatwebsite\Excel\Facades\Excel;

/**
 * Class QuotationRequestFileGenerator
 * @package Insight\Quotations
 */
class QuotationRequestFileGenerator
{

    /**
     * @var QuotationRequest
     */
    private $quotationRequest;

    /**
     * @param QuotationRequest $quotationRequest
     */
    public function __construct(QuotationRequest $quotationRequest)
    {
        $this->quotationRequest = $quotationRequest;
    }

    /**
     * Generate an spreadsheet containing quotation request items and save to storage
     */
    public function generate()
    {

        $data = [];

        foreach ($this->quotationRequest->quotations as $quotation) {
            $data[] =
                [
                    'Quotation_Id'        => $quotation->quotation_id,
                    'Product_Description' => $quotation->product_description,
                    'UOM'                 => $quotation->uom,
                    'Quantity'            => $quotation->volume,
                    'Current_Price'       => $quotation->current_price,
                    'Currency'            => $quotation->current_price_currency,
                ];
        }

        $file = Excel::create(time() . '_' . $this->quotationRequest->quotation_request_id, function ($excel) use ($data) {

            $excel->setCreator($this->quotationRequest->updatedBy->name())
                ->setCompany($this->quotationRequest->updatedBy->company->name);

            $excel->sheet($this->quotationRequest->quotation_request_id, function ($sheet) use ($data) {

                $sheet->fromArray($data, null, 'A1', false);

            });

        })->save('xlsx');

        return $file;
    }
}
