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
use Insight\Quotations\Events\QuotationWasClosed;
use Insight\Quotations\Events\QuotationWasReceived;
use Laracasts\Commander\Events\DispatchableTrait;
use Laracasts\Commander\Events\EventGenerator;

/**
 * Class Quotation
 * @package Insight\Quotations
 */
class Quotation extends \Eloquent implements StatefulInterface
{

    use AclTrait, EventGenerator, DispatchableTrait, FiniteStateMachine, FiniteAuditTrail, MoneyTrait, SoftDeletingTrait;

    /**
     * @var string
     */
    protected $table = 'quotations';

    /**
     *
     */
    const
        STATE_INITIAL = 'DRA';

    /**
     * @var array
     */
    protected $fillable = [
        'quotation_request_id', 'created_by_id', 'company_id', 'supplier_id', 'request_id', 'product_description',
        'uom', 'volume', 'current_price', 'current_price_currency', 'quotation_date', 'supplier_reference', 'suppliers_product_name',
        'suppliers_product_description', 'suppliers_product_sku', 'suppliers_uom', 'suppliers_quantity', 'unit_price', 'price_currency',
        'total_price', 'valid_until', 'delivery_terms', 'payment_terms', 'state', 'updated_by_id'
    ];

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
    public static $currencyFields = ['current_price', 'unit_price', 'total_price'];

    /**
     * @var array
     */
    private $moneyFields = ['current_price', 'unit_price'];

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
            $model->quotation_id = (string) $model->generateUniqueId();
        });
        static::finiteAuditTrailBoot();
    }

    /**
     * @return bool|string
     */
    public static function generateUniqueId()
    {
        $idGenerator = new UniqueIdGenerator();

        return $idGenerator->generateId(new self, 'quotation_id', 'QT-');
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
            'save_draft'   => 'save draft',
            'submit'       => 'submit',
            'delete_draft' => 'delete draft',
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
        $lastTransition = QuotationStateTransition::where('quotation_id', $this->attributes['id'])->get()->last();

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
                    'properties' => ['label' => 'Submitted', 'deletable' => true, 'editable' => true],
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
                'save_draft'    => ['from' => ['DRA'], 'to' => 'DRA'],
                'submit'        => ['from' => ['DRA'], 'to' => 'SUB'],
                'delete_draft'  => ['from' => ['DRA'], 'to' => 'CLS'],
                'receive_quote' => ['from' => ['SUB'], 'to' => 'RCV'],
                'complete'      => ['from' => ['RCV'], 'to' => 'COM'],
                'close'         => ['from' => ['DRA', 'SUB', 'RCV', 'COM'], 'to' => 'CLS'],
            ],
            'callbacks'   => [
                'before' => [
//                    ['on' => 'save draft', 'do' => [$this, 'beforeTransitionT12']],
//                    ['on' => 'resubmit', 'do' => [$this, 'beforeResubmit']],
//                    ['from' => 'REV', 'to' => 'SRC', 'do' => function ($myStatefulInstance, $transitionEvent) {
//                        echo "Before callback from 's2' to 's3'";// debug
//                        Log::info("Before callback from 's2' to 's3'");// debug
//                    }],
//                    ['from' => '-complete', 'to' => ['SRC', 'PRP'], 'do' => [$this, 'fromStatesS1S2ToS1S3']],
                ],
                'after'  => [
                    ['on' => 'submit', 'do' => [$this, 'afterSubmitQuote']],
                    ['on' => 'receive_quote', 'do' => [$this, 'afterReceiveQuote']],
                    ['on' => 'close', 'do' => [$this, 'afterCloseQuotation']],
//                    ['from' => 'all', 'to' => 'all', 'do' => [$this, 'afterAllTransitions']],
                ],
            ],
        ];
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     */
    public function afterReceiveQuote($myStatefulInstance, $transitionEvent)
    {
        $this->raise(new QuotationWasReceived($this));
        $this->dispatchEventsFor($this);

        $this->attachQuoteToDefaultProposal($myStatefulInstance, $transitionEvent);

        if ($this->quotationRequest->allQuotationsAreReceived()) {
            if ($this->quotationRequest->can('receive_quotation')) {
                $this->quotationRequest->apply('receive_quotation');
            }
        }
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     */
    public function afterSubmitQuote($myStatefulInstance, $transitionEvent)
    {
        $this->setProductRequestToPendingQuotation();
    }

    protected function setProductRequestToPendingQuotation()
    {
        if ($this->productRequest->can('submit_quotation_request')) {
            $this->productRequest->apply('submit_quotation_request');
        }
        $handler = new AddNewCommentCommandHandler();
        $submittedBy = $this->updatedBy ?: $this->createdBy;
        $handler->handle(new AddNewCommentCommand(
            $this->productRequest,
            $submittedBy->id,
            "Product Request sent for supplier quotation by {$submittedBy->name()}."
        ));

    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     */
    public function attachQuoteToDefaultProposal($myStatefulInstance, $transitionEvent)
    {
        if (count($this->productRequest->proposals) === 1) {
            $proposal = $this->productRequest->proposals->first();

            if ($proposal->getState() === 'DRA' && !$proposal->price) {
                $proposal->quotation_id = $this->id;
                $proposal->save();
            }

        }
    }

    public function afterCloseQuotation($myStatefulInstance, $transitionEvent)
    {
        $this->raise(new QuotationWasClosed($this));
        $this->dispatchEventsFor($this);
    }

    /**
     * Apply query scope where state == RCV
     *
     * @param $query
     * @return mixed
     */
    public function scopeReceived($query)
    {
        return $query->where('state', 'RCV');
    }

    /**
     * @return mixed
     */
    public function productRequest()
    {
        return $this->belongsTo('Insight\ProductRequests\ProductRequest', 'request_id');
    }

    /**
     * @return mixed
     */
    public function quotationRequest()
    {
        return $this->belongsTo('Insight\Quotations\QuotationRequest');
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
    public function supplier()
    {
        return $this->belongsTo('Insight\Companies\Supplier');
    }

    /**
     * User whom originally created the quotation
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
    public function updatedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'updated_by_id');
    }

    /**
     * @return mixed
     */
    public function productProposals()
    {
        return $this->hasMany('Insight\ProductProposals\ProductProposal');
    }

}
