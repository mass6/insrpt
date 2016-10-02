<?php

namespace Insight\Sourcing;

use Insight\Users\User;

/**
 * Class ImportSourcingRequestCommand
 * @package Insight\Sourcing
 */
class ImportSourcingRequestCommand
{

    /**
     * @var
     */
    public $customer_id;
    /**
     * @var
     */
    public $received_on;
    /**
     * @var
     */
    public $batch;
    /**
     * @var
     */
    public $status;
    /**
     * @var
     */
    public $assigned_to_id;
    /**
     * @var
     */
    public $importfile;
    /**
     * @var
     */
    public $user;


    /**
     * @param $customer_id
     * @param $received_on
     * @param $batch
     * @param $status
     * @param $assigned_to_id
     * @param $importfile
     * @param User $user
     */
    public function __construct($customer_id, $received_on, $batch, $status, $assigned_to_id, $importfile, User $user)
    {
        $this->customer_id = $customer_id;
        $this->received_on = $received_on;
        $this->batch = $batch;
        $this->status = $status;
        $this->assigned_to_id = $assigned_to_id;
        $this->importfile = $importfile;
        $this->user = $user;
    }
    
}