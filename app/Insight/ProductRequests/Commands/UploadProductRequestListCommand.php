<?php

namespace Insight\ProductRequests\Commands;

use Insight\Users\User;

/**
 * Class UploadProductRequestListCommand
 * @package Insight\ProductRequests\Commands
 */
class UploadProductRequestListCommand
{

    /**
     * @var User
     */
    public $user;
    /**
     * @var User
     */
    public $requester;
    /**
     * @var
     */
    public $company_id;
    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $uploadFile;
    /**
     * @var
     */
    public $referenceFields;
    /**
     * @var
     */
    public $transition;

    /**
     * @param User $user
     * @param User $requester
     * @param $company_id
     * @param $name
     * @param $uploadFile
     * @param $referenceFields
     * @param $transition
     */
    public function __construct(User $user, User $requester, $company_id, $name, $uploadFile, $referenceFields, $transition)
    {
        $this->user = $user;
        $this->requester = $requester;
        $this->company_id = $company_id;
        $this->name = $name;
        $this->uploadFile = $uploadFile;
        $this->referenceFields = $referenceFields;
        $this->transition = $transition;
    }
}
