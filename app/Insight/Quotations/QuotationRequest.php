<?php

namespace Insight\Quotations;

use Finite\StatefulInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Insight\Comments\AddNewCommentCommand;
use Insight\Comments\AddNewCommentCommandHandler;
use Insight\Libraries\AclTrait;
use Insight\Libraries\MoneyTrait;
use Insight\Libraries\StateMachine\FiniteAuditTrail;
use Insight\Libraries\StateMachine\FiniteStateMachine;
use Insight\Libraries\UniqueIdGenerator;
use Insight\Quotations\Events\QuotationRequestWasClosed;
use Laracasts\Commander\Events\DispatchableTrait;
use Laracasts\Commander\Events\EventGenerator;

/**
 * Class QuotationRequest
 * @package Insight\Quotations
 */
class QuotationRequest extends \Eloquent implements StatefulInterface
{

    use AclTrait, EventGenerator, DispatchableTrait, FiniteStateMachine, FiniteAuditTrail, MoneyTrait, SoftDeletingTrait;

    /**
     * @var string
     */
    protected $table = 'quotation_requests';
    /**
     *
     */
    const
        STATE_INITIAL = 'DRA';

    /**
     * @var array
     */
    protected $fillable = ['created_by_id', 'company_id', 'supplier_id', 'send_to_supplier', 'delivery_email', 'email_delivery_status', 'message', 'updated_by_id'];

    /**
     * @var string
     */
    protected $stateField = 'state';

    /**
     * @var
     */
    private $lastState;

    /**
     * @var array
     */
    private $moneyFields = [];

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
            $model->quotation_request_id = (string) $model->generateUniqueId();
        });
        static::finiteAuditTrailBoot();
    }

    /**
     * @return bool|string
     */
    public static function generateUniqueId()
    {
        $idGenerator = new UniqueIdGenerator();

        return $idGenerator->generateId(new self, 'quotation_request_id', 'QR-');
    }

    /**
     * @return string
     */
    public static function getInitialState()
    {
        return static::STATE_INITIAL;
    }

    /**
     * @param null $name
     * @param bool|false $searchByValue
     * @return array|mixed
     */
    public static function transitionLabel($name = null, $searchByValue = false)
    {
        $labels = [
            'save_draft'     => 'save draft',
            'submit'         => 'submit',
            'delete_draft'   => 'delete draft',
            'save_submitted' => 'save changes',
            'save_received'  => 'save changes',
            'complete'       => 'complete',
            'close'          => 'close',
        ];

        if (isset($name)) {
            if ($searchByValue) {
                return array_search($name, $labels);
            } else {
                return $labels[$name];
            }
        }

        return $labels;
    }
    public static function getEmailDeliveryStatus($event)
    {
        $statuses = [
            'send' => 'Delivered',
            'open' => 'Read',
            'click' => 'Read',
            'hard_bounce' => 'Bounced',
            'soft_bounce' => 'Bounced',
            'spam' => 'Rejected as Spam',
            'reject' => 'Rejected',
        ];

        return array_get($statuses, $event);
    }

    /**
     * @return array
     */
    public static function getStateLabels()
    {
        $stateMachine = new static;
        $states = $stateMachine->stateMachineConfig()['states'];
        $stateLabels = [];
        foreach ($states as $state => $config) {
            $stateLabels[$state] = $config['properties']['label'];
        }

        return $stateLabels;
    }

    /**
     * @return mixed
     */
    public function currentStateLabel()
    {
        return static::stateMachineConfig()['states'][$this->state]['properties']['label'];
    }


    /**
     * @return mixed
     */
    public function lastTransition()
    {
        $lastTransition = QuotationRequestStateTransition::where('quotation_request_id', $this->attributes['id'])->get()->last();

        return $lastTransition;
    }

    /**
     * @return array
     */
    protected function stateMachineConfig()
    {
        return [
            'graph'       => 'QuotationRequestStateMachine',
            'states'      => [
                'DRA' => [
                    'type'       => 'initial',
                    'properties' => ['label' => 'Draft', 'deletable' => true, 'editable' => true],
                ],
                'SUB' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Sent', 'deletable' => true, 'editable' => true],
                ],
                'RCV' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Received', 'deletable' => false, 'editable' => true],
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
                'save_draft'        => ['from' => ['DRA'], 'to' => 'DRA'],
                'submit'            => ['from' => ['DRA'], 'to' => 'SUB'],
                'delete_draft'      => ['from' => ['DRA'], 'to' => 'CLS'],
                'save_submitted'    => ['from' => ['SUB'], 'to' => 'SUB'],
                'receive_quotation' => ['from' => ['SUB'], 'to' => 'RCV'],
                'save_received'     => ['from' => ['SUB'], 'to' => 'RCV'],
                'complete'          => ['from' => ['RCV'], 'to' => 'COM'],
                'close'             => ['from' => ['DRA', 'SUB', 'RCV', 'COM'], 'to' => 'CLS'],
            ],
            'callbacks'   => [
                'before' => [
//                    ['on' => 'save draft', 'do' => [$this, 'beforeTransitionT12']],
//                    ['on' => 'resubmit', 'do' => [$this, 'beforeResubmit']],
//                    ['from' => 'REV', 'to' => 'SRC', 'do' => function ($myStatefulInstance, $transitionEvent) {
////                        echo "Before callback from 's2' to 's3'";// debug
//                        Log::info("Before callback from 's2' to 's3'");// debug
//                    }],
//                    ['from' => '-complete', 'to' => ['SRC', 'PRP'], 'do' => [$this, 'fromStatesS1S2ToS1S3']],
                ],
                'after'  => [
                    ['on' => 'submit', 'do' => [$this, 'afterSubmitQuotation']],
                    ['on' => 'receive_quotation', 'do' => [$this, 'afterReceiveQuotation']],
                    ['on' => 'close', 'do' => [$this, 'afterCloseRequest']],
//                    ['from' => 'all', 'to' => 'all', 'do' => [$this, 'afterAllTransitions']],
                ],
            ],
        ];
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     */
    public function afterSubmitQuotation($myStatefulInstance, $transitionEvent)
    {
        $this->setQuotationStateToSubmitted($myStatefulInstance,$transitionEvent);
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     */
    public function afterReceiveQuotation($myStatefulInstance, $transitionEvent)
    {
        $this->setQuotationStateToReceived($myStatefulInstance,$transitionEvent);

    }

    public function afterCloseRequest($myStatefulInstance, $transitionEvent)
    {
        $this->raise(new QuotationRequestWasClosed($this));
        $this->dispatchEventsFor($this);
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     */
    public function setQuotationStateToSubmitted($myStatefulInstance, $transitionEvent)
    {
        foreach ($this->quotations as $quotation) {
            $quotation->apply('submit');
        }
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     */
    public function setQuotationStateToReceived($myStatefulInstance, $transitionEvent)
    {
        foreach ($this->quotations as $quotation) {
            if ($quotation->can('receive_quote')) {
                $quotation->apply('receive_quote');
            }
        }
    }

    /**
     * @param $quotation
     */
    public function applyReadyForPricingToProductRequests($quotation)
    {
        if ($quotation->productRequest->can('submit_for_pricing')) {
            $quotation->productRequest->apply('submit_for_pricing');
            $handler = new AddNewCommentCommandHandler();
            $handler->handle(new AddNewCommentCommand(
                $quotation->productRequest,
                $this->updatedBy->id,
                "Product Request submitted for pricing by {$this->updatedBy->name()}."
            ));

        }
    }

    /**
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo('Insight\Companies\Company');
    }

    /**
     * @return mixed
     */
    public function createdBy()
    {
        return $this->belongsTo('Insight\Users\User', 'created_by_id');
    }

    /**
     * @return mixed
     */
    public function updatedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'updated_by_id');
    }

    /**
     * @return mixed
     */
    public function supplier()
    {
        return $this->belongsTo('Insight\Companies\Supplier');
    }

    /**
     * @return mixed
     */
    public function quotations()
    {
        return $this->hasMany('Insight\Quotations\Quotation');
    }

    public function allQuotationsAreReceived()
    {
        foreach ($this->quotations as $quotation) {
            if ($quotation->state !== 'RCV') {
                return false;
            }
        }
        return true;
    }
}
