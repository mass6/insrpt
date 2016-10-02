<?php

namespace Insight\ProductRequests;

use Carbon\Carbon;
use Finite\StatefulInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Insight\Libraries\AclTrait;
use Insight\Libraries\MoneyTrait;
use Insight\Libraries\StateMachine\FiniteAuditTrail;
use Insight\Libraries\StateMachine\FiniteStateMachine;
use Insight\ProductProposals\Commands\AttachNewProductProposalCommand;
use Insight\ProductProposals\Commands\AttachNewProductProposalCommandHandler;
use Insight\ProductProposals\Commands\CancelProductProposalCommand;
use Insight\ProductProposals\Commands\CancelProductProposalCommandHandler;
use Laracasts\Commander\Events\EventGenerator;

/**
 * Class ProductRequest
 * @package Insight\ProductRequests
 */
class ProductRequest extends \Eloquent implements StatefulInterface
{

    use AclTrait, EventGenerator, FiniteStateMachine, FiniteAuditTrail, MoneyTrait, SoftDeletingTrait;

    /**
     * @var string
     */
    protected $table = 'product_requests';

    /**
     * Additional date fields to be merged into the Carbon objects array (default = created_at, updated_at)
     *
     * @var array
     */
    protected $dates = ['completed_at'];
    /**
     *
     */
    const
        STATE_INITIAL = 'NEW';

    /**
     * @var array
     */
    public static $purchaseRecurrence = [
        'AHC' => 'One-time/Ad-hoc',
        'MON' => 'Monthly',
        'ANN' => 'Annually',
    ];

        /**
     * @var array
     */
    public static $reasonsClosed = [
        'DUP' => 'Duplicate',
        'WNS' => 'Will Not Source',
        'AVL' => 'Product Already Available',
    ];
    /**
     * @var array
     */
    protected $fillable = [
        'created_by_id', 'requested_by_id', 'list_id', 'company_id', 'product_description', 'sku', 'uom', 'category', 'purchase_recurrence', 'first_time_order_quantity', 'volume_requested', 'current_supplier',
        'current_supplier_contact', 'current_price', 'current_price_currency', 'remarks', 'updated_by_id', 'state',
        'reference1', 'reference2', 'reference3', 'reference4', 'closed_at', 'reason_closed', 'cataloguing_item_code', 'cataloguing_product_name'
    ];
    /**
     * @var string
     */
    protected $stateField = 'state';

    /**
     * @var
     */
    private $state;

    /**
     * @var
     */
    private $lastState;

    /**
     * @var array
     */
    public static $currencyFields = [
        'current_price',
    ];

    /**
     * @var array
     */
    private $moneyFields = [
        'current_price',
    ];


    /**
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->initStateMachine();
        parent::__construct($attributes);
        $this->initAuditTrail();
    }

    /**
     *
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->request_id = (string) $model->generateRequestId();
        });
        static::finiteAuditTrailBoot();
    }

    /**
     * @return string
     */
    public static function generateRequestId()
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $request_id = '';
        foreach (array_rand(str_split($letters), 4) as $letter) $request_id .= $letters[$letter];
        $request_id .= '-';
        foreach (array_rand(str_split($numbers), 4) as $number) $request_id .= $numbers[$number];

        // ensure request_id generated is unique
        if (static::where('request_id', $request_id)->first()) {
            return self::generateRequestId();
        }

        return $request_id;
    }

    /**
     * @return array
     */
    public static function purchaseRecurrenceOptions()
    {
        return static::$purchaseRecurrence;
    }

    /**
     * @return string
     */
    public static function getInitialState()
    {
        return static::STATE_INITIAL;
    }

    /**
     * @param null $stateId
     *
     * @return array
     */
    public static function getStateLabel($stateId = null)
    {
        $labels = [
            'NEW' => 'New',
            'DRA' => 'Draft',
            'REV' => 'In Review',
            'INP' => 'Pending Input',
            'SRC' => 'Sourcing',
            'PEQ' => 'Pending Quotation',
            'PRP' => 'Pending Proposal',
            'APP' => 'Pending Approval',
            'APR' => 'Approved',
            'REJ' => 'Proposal Rejected',
            'CAT' => 'Ready to Catalogue',
            'DEP' => 'Ready to Deploy',
            'COM' => 'Complete',
            'CLS' => 'Closed',
        ];
        if ($stateId) {
            return $labels[$stateId];
        }
        else {
            return $labels;
        }
    }

    /**
     * @param null $name
     * @param bool|false $searchByValue
     * @return array|mixed
     */
    public static function transitionLabel($name = null, $searchByValue = false)
    {
        $labels = [
            'save_draft'             => 'save draft',
            'submit_request'         => 'submit request',
            'delete_draft'           => 'delete draft',

            'save_reviewing'         => 'save',
            'submit_for_sourcing'    => 'submit for sourcing',
            'reassign_to_requester'  => 'reassign to requester',

            'save_sourcing'          => 'save',
            'submit_for_pricing'     => 'submit for proposal',
            'revert_for_review'      => 'reassign to reviewer',

            'save_pending_quotation' => 'save',

            'save_pending_proposal'  => 'save',
            'submit_proposal'        => 'submit proposal',
            'recall_proposal'        => 'recall proposal',

            'save_pending_approval'  => 'save',
            'approve'                => 'approve',

            'save_proposal_rejected' => 'save',

            'submit_for_cataloguing' => 'submit for cataloguing',

            'save_cataloguing'       => 'save',
            'submit_for_deployment'  => 'submit for deployment',

            'save_deployment'        => 'save',
            'revert_to_cataloguing'  => 'reassign to cataloguing',

            'complete'               => 'complete request',
            'close'                  => 'close request',
        ];

        if (isset($name)) {
            if ($searchByValue) {
                return array_search($name, $labels);
            } else if (isset($labels[$name])) {
                return $labels[$name];
            } else {
                return $name;
            }
        }

        return $labels;
    }

    /**
     * @return mixed
     */
    public function purchaseRecurrenceLabel()
    {
        return static::$purchaseRecurrence[$this->purchase_recurrence];
    }

    /**
     * @return mixed
     */
    public function currentStateLabel()
    {
        return static::stateMachineConfig()['states'][$this->state]['properties']['label'];
    }

    /**
     * @return array
     */
    protected function stateMachineConfig()
    {
        return [
            //'class'       => get_class(),//useful?
            'graph'       => 'TicketStateMachine',
            'states'      => [
                'NEW' => [
                    'type'       => 'initial',
                    'properties' => ['label' => 'New', 'deletable' => true, 'editable' => true],
                ],
                'DRA' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Draft', 'deletable' => true, 'editable' => true],
                ],
                'REV' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'In Review', 'deletable' => true, 'editable' => true],
                ],
                'INP' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Pending Input', 'deletable' => true, 'editable' => true],
                ],
                'SRC' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Sourcing', 'deletable' => true, 'editable' => true],
                ],
                'PEQ' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Pending Quotation', 'deletable' => true, 'editable' => true],
                ],
                'PRP' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Pending Proposal', 'deletable' => true, 'editable' => true],
                ],
                'APP' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Pending Approval', 'deletable' => true, 'editable' => true],
                ],
                'APR' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Approved', 'deletable' => true, 'editable' => true],
                ],
                'REJ' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Proposal Rejected', 'deletable' => true, 'editable' => true],
                ],
                'CAT' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Ready to Catalogue', 'deletable' => true, 'editable' => true],
                ],
                'DEP' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Ready to Deploy', 'deletable' => true, 'editable' => true],
                ],
                'COM' => [
                    'type'       => 'final',
                    'properties' => ['label' => 'Complete', 'deletable' => false, 'editable' => true],
                ],
                'CLS' => [
                    'type'       => 'final',
                    'properties' => ['label' => 'Closed'],
                ]
            ],
            'transitions' => [
                // save transitions
                'save_draft'             => ['from' => ['NEW', 'DRA'], 'to' => 'DRA'],
                'save_reviewing'         => ['from' => ['REV'], 'to' => 'REV'],
                'save_sourcing'          => ['from' => ['SRC'], 'to' => 'SRC'],
                'save_pending_quotation' => ['from' => ['PEQ'], 'to' => 'PEQ'],
                'save_pending_proposal'  => ['from' => ['PRP'], 'to' => 'PRP'],
                'save_pending_approval'  => ['from' => ['APP'], 'to' => 'APP'],
                'save_proposal_rejected' => ['from' => ['REJ'], 'to' => 'REJ'],
                'save_cataloguing'       => ['from' => ['CAT'], 'to' => 'CAT'],
                'save_deployment'        => ['from' => ['DEP'], 'to' => 'DEP'],

                // progress forward transitions
                'submit_request'         => ['from' => ['NEW', 'DRA', 'INP'], 'to' => 'REV'],
                'submit_for_sourcing'    => ['from' => ['REV', 'REJ', 'PEQ', 'PRP', 'APP'], 'to' => 'SRC'],
                'submit_for_pricing'     => ['from' => ['REV', 'SRC', 'PEQ', 'REJ'], 'to' => 'PRP'],
                'submit_for_cataloguing' => ['from' => ['APR'], 'to' => 'CAT'],
                'submit_for_deployment'  => ['from' => ['CAT'], 'to' => 'DEP'],

                // reverting transitions
                'reassign_to_requester'  => ['from' => ['REV', 'SRC', 'PEQ'], 'to' => 'INP'],
                'revert_for_review'      => ['from' => ['SRC', 'PEQ', 'PRP', 'REJ', 'CAT'], 'to' => 'REV'],
                'revert_to_cataloguing'  => ['from' => ['DEP'], 'to' => 'CAT'],
                'reopen'                 => ['from' => ['CLS', 'COM'], 'to' => 'REV'],

                // closing transitions
                'complete'               => ['from' => ['APR', 'CAT', 'DEP'], 'to' => 'COM'],
                'close'                  => ['from' => ['REV', 'INP', 'SRC', 'PEQ', 'PRP', 'APP', 'APR', 'REJ', 'CAT', 'DEP'], 'to' => 'CLS'],

                // deleting transitions
                'delete_draft'           => ['from' => ['DRA'], 'to' => 'CLS'],

                // hidden transitions
                'submit_quotation_request' => ['from' => ['REV', 'SRC'], 'to' => 'PEQ'],
                'submit_proposal'        => ['from' => ['REV', 'SRC', 'PEQ', 'PRP', 'APP', 'APR', 'REJ'], 'to' => 'APP'],
                'delete_active_proposal' => ['from' => ['APP', 'APR'], 'to' => 'PRP'],
                'recall_proposal'        => ['from' => ['APP', 'APR'], 'to' => 'PRP'],
                'reject_proposal'        => ['from' => ['APP'], 'to' => 'REJ'],
                'approve'                => ['from' => ['APP'], 'to' => 'APR'],
            ],
            'callbacks'   => [
                'before' => [
                    ['on' => 'close', 'do' => [$this, 'beforeClose']],
                    ['on' => 'complete', 'do' => [$this, 'beforeComplete']],
//                    ['from' => '-complete', 'to' => ['SRC', 'PRP'], 'do' => [$this, 'fromStatesS1S2ToS1S3']],
//                    }],
//                        Log::info("Before callback from 's2' to 's3'");// debug
////                        echo "Before callback from 's2' to 's3'";// debug
//                    ['from' => 'REV', 'to' => 'SRC', 'do' => function ($myStatefulInstance, $transitionEvent) {
//                    ['on' => 'resubmit', 'do' => [$this, 'beforeResubmit']],
                ],
                'after'  => [
//                    ['from' => 'all', 'to' => 'all', 'do' => [$this, 'afterAllTransitions']],
                    ['on' => 'submit_for_sourcing', 'do' => [$this, 'createBlankProposal']],
                ],
            ],
        ];
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function beforeClose($myStatefulInstance, $transitionEvent)
    {
        $this->closed_at = Carbon::now();
        $this->cancelProposals($this->updatedBy);
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function beforeComplete($myStatefulInstance, $transitionEvent)
    {
        $this->completed_at = Carbon::now();
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function createBlankProposal($myStatefulInstance, $transitionEvent)
    {

        // TODO: Add conditional based to add default first proposal based on company settings
        if (!count($this->proposals)) {

            $handler = new AttachNewProductProposalCommandHandler;
            $handler->handle(new AttachNewProductProposalCommand(
                $this->updatedBy, $this, null, $this->company_id, $this->product_description, $this->product_description,
                $this->uom, $this->volume_requested, $this->sku, $this->price, $this->price_currency,
                false, null, 'save_draft', []
            ));
        }
    }


    /**
     * @param $user
     */
    protected function cancelProposals($user)
    {
        foreach ($this->proposals as $proposal) {
            $handler = new CancelProductProposalCommandHandler;
            $handler->handle(new CancelProductProposalCommand($proposal, $user));
        }
    }

    /**
     * @return mixed
     */
    public function lastTransition()
    {
        $lastTransition = ProductRequestStateTransition::where('product_request_id', $this->attributes['id'])->get()->last();

        return $lastTransition;
    }

    /**
     * User whom originally created the product request
     *
     * @return mixed
     */
    public function createdBy()
    {
        return $this->belongsTo('Insight\Users\User', 'created_by_id');
    }

    /**
     * @return mixed
     */
    public function requestedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'requested_by_id');
    }

    /**
     * Company whom this product request belongs to
     *
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo('Insight\Companies\Company');
    }

    /**
     * User whom last updated the product request
     *
     * @return mixed
     */
    public function updatedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'updated_by_id');
    }

    /**
     * Associated images for this product request
     *
     * @return mixed
     */
    public function images()
    {
        return $this->morphMany('Insight\ProductDefinitions\ProductImage', 'imageable')->where('imageable_id', '<>', '');
    }

    /** Sourcing Request can have many associated comments
     * @return mixed
     */
    public function comments()
    {
        return $this->morphMany('Insight\Comments\Comment', 'commentable');
    }

    /**
     * Associated images for this product request
     *
     * @return mixed
     */
    public function attachments()
    {
        return $this->morphMany('Insight\ProductDefinitions\ProductAttachment', 'attachable')->where('attachable_id', '<>', '');
    }

    /**
     * @return mixed
     */
    public function contracts()
    {
        return $this->belongsToMany('Insight\Portal\Contracts\Contract')->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function productRequestList()
    {
        return $this->belongsTo('Insight\ProductRequests\ProductRequestList', 'list_id');
    }

    /**
     * @return mixed
     */
    public function quotations()
    {
        return $this->hasMany('Insight\Quotations\Quotation', 'request_id');
    }

    /**
     * @return mixed
     */
    public function quotationsReceived()
    {
        return $this->hasMany('Insight\Quotations\Quotation', 'request_id')->where('state', 'RCV');
    }

    /**
     * @return mixed
     */
    public function proposals()
    {
        return $this->hasMany('Insight\ProductProposals\ProductProposal', 'request_id');
    }


    /**
     * @return bool
     */
    private function hasProposals()
    {
        return count($this->proposals) ? true : false;
    }

    /**
     * @return bool
     */
    private function doesNotHaveProposals()
    {
        return count($this->proposals) ? false : true;
    }


    /**
     * @return bool
     */
    public function canAttachProposal()
    {
        foreach ($this->proposals as $proposal) {
            if ($proposal->isActive()) {
                return false;
            }
        }

        return true;
    }

    public function setAssociatedPortalContracts($contracts)
    {
        $this->contracts()->sync($contracts);
    }
}