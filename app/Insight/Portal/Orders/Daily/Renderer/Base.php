<?php
namespace Insight\Portal\Orders\Daily\Renderer;

use Maatwebsite\Excel\Facades\Excel;

class Base
{
    protected $_ordersData;
    protected $_spreadsheetData;
    protected $_file;

    public function setOrdersData($data)
    {
        $this->_ordersData = $data;
        return $this;
    }

    public function getOrdersData()
    {
        return $this->_ordersData;
    }

    public function setSpreadsheetData($data)
    {
        $this->_spreadsheetData = $data;
        return $this;
    }

    public function prepareSpreadsheetData()
    {
        return $this;
    }

    public function getFilename()
    {
        return mktime(date('now')) . '-OrderReport';
    }

    public function getTitle()
    {
        return 'DailyOrderReport';
    }

    public function getDescription()
    {
        return 'Approved orders from the previous day';
    }

    public function getSheetName()
    {
        return 'Yesterday';
    }

    public function createSpreadsheet()
    {
        $this->prepareSpreadsheetData();

        if(!empty($this->_spreadsheetData)) {
            $data = $this->_spreadsheetData;
            Excel::create($this->getFilename(), function ($excel) use ($data) {
                $excel->setTitle($this->getTitle());
                $excel->setDescription($this->getDescription());
                $file = $excel->sheet($this->getSheetName(), function ($sheet) use ($data) {
                    $sheet->fromArray($data, null, 'A1', false);
                })->store('xlsx');
                $this->_file = $file;
            });
            return $this->_file;
        }
    }
}

