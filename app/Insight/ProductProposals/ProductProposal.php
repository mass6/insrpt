<?php

namespace Insight\ProductProposals;

use Carbon\Carbon;
use Finite\StatefulInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Event;
use Insight\Libraries\AclTrait;
use Insight\Libraries\MoneyTrait;
use Insight\Libraries\StateMachine\FiniteAuditTrail;
use Insight\Libraries\StateMachine\FiniteStateMachine;
use Insight\ProductProposals\Commands\AssignProductProposalCommand;
use Insight\ProductProposals\Commands\AssignProductProposalCommandHandler;
use Insight\ProductProposals\Events\ProductProposalWasAssigned;
use Laracasts\Commander\Events\EventGenerator;

/**
 * Class ProductProposal
 * @package Insight\ProductProposals
 */
class ProductProposal extends \Eloquent implements StatefulInterface
{

    use AclTrait, EventGenerator, FiniteStateMachine, FiniteAuditTrail, MoneyTrait, SoftDeletingTrait;

    /**
     * @var string
     */
    protected $table = 'product_proposals';

    /**
     *
     */
    const
        STATE_INITIAL = 'NEW';

    /**
     * @var array
     */
    protected $fillable = ['created_by_id', 'company_id', 'request_id', 'quotation_id', 'product_name', 'product_description',
        'uom', 'price', 'volume', 'sku', 'price_currency', 'supplier_id', 'supplier_contact', 'display_quotation_details',
        'remarks', 'updated_by_id', 'state', 'assigned_to_id', 'auto_approved'];

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
    public static $currencyFields = ['price'];

    /**
     * @var array
     */
    private $moneyFields = [
        'price',
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
            $model->proposal_id = (string) $model->generateProposalId();
        });
        static::finiteAuditTrailBoot();
    }

    /**
     * @return string
     */
    public static function generateProposalId()
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $proposal_id = 'PP-';
        foreach (array_rand(str_split($letters), 4) as $letter) $proposal_id .= $letters[$letter];
        $proposal_id .= '-';
        foreach (array_rand(str_split($numbers), 4) as $number) $proposal_id .= $numbers[$number];

        // ensure proposal_id generated is unique
        if (static::where('proposal_id', $proposal_id)->first()) {
            self::generateProposalId();
        }

        return $proposal_id;
    }

    public function getDates()
    {
        return ['created_at', 'updated_at', 'assigned_at'];
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
            'save_draft'      => 'save draft',
            'submit_proposal' => 'submit proposal',
            'update'          => 'update',
            'recall_proposal' => 'recall proposal',

            'approve'         => 'approve proposal',
            'final_approve'   => 'approve',
            'reject'          => 'reject',

            'delete'          => 'delete',
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
     * @return array
     */
    protected function filterTransitions()
    {
        $transitions = $this->getCurrentState()->getTransitions();
        if (in_array('submit_proposal', $transitions) && ! $this->isSubmittable()) {
            $transitions = array_diff($transitions, ['submit_proposal']);
        }

        return $transitions;
    }


    /**
     * @return bool
     */
    public function isActive()
    {
        return in_array($this->getState(), ['APR', 'APP']);
    }


    /**
     * @return bool
     */
    protected function isSubmittable()
    {
        return $this->productRequest ? $this->productRequest->canAttachProposal() : true;
    }


    /**
     * @return bool
     */
    public function isEditable()
    {
        return ! in_array($this->getState(), ['CAN']);
    }

    /**
     * @return array
     */
    protected function stateMachineConfig()
    {
        return [
            //'class'       => get_class(),//useful?
            'graph'       => 'ProductProposalStateMachine',
            'states'      => [
                'NEW' => [
                    'type'       => 'initial',
                    'properties' => ['label' => 'New', 'deletable' => true, 'editable' => true],
                ],
                'DRA' => [
                    'type'       => 'normal',
                    'properties' => ['label' => 'Draft', 'deletable' => true, 'editable' => true],
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
                    'properties' => ['label' => 'Rejected', 'deletable' => true, 'editable' => true],
                ],
                'CAN' => [
                    'type'       => 'final',
                    'properties' => ['label' => 'Cancelled', 'deletable' => false, 'editable' => true],
                ],
            ],
            'transitions' => [
                'save_draft'      => ['from' => ['NEW', 'DRA', 'REJ'], 'to' => 'DRA'],
                'submit_proposal' => ['from' => ['NEW', 'DRA', 'REJ'], 'to' => 'APP'],
                'update'          => ['from' => ['APP'], 'to' => 'APP'],
                'recall_proposal' => ['from' => ['APP', 'APR'], 'to' => 'DRA'],

                'approve'         => ['from' => ['APP'], 'to' => 'APP'],
                'final_approve'   => ['from' => ['APP'], 'to' => 'APR'],
                'reject'          => ['from' => ['APP'], 'to' => 'REJ'],

                'delete'          => ['from' => ['DRA','APP','APR','REJ'], 'to' => 'CAN'],
            ],
            'callbacks'   => [
                'before' => [
                    ['on' => 'approve', 'do' => [$this, 'beforeApprove']],
                    ['on' => 'delete', 'do' => [$this, 'beforeDelete']],
                ],
                'after'  => [
                    ['on' => 'submit_proposal', 'do' => [$this, 'afterSubmitted']],
                    ['on' => 'recall_proposal', 'do' => [$this, 'afterRecalled']],
                    ['on' => 'approve', 'do' => [$this, 'afterApprove']],
                    ['on' => 'reject', 'do' => [$this, 'afterReject']],
                ],
            ],
        ];
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function afterSubmitted($myStatefulInstance, $transitionEvent)
    {
        if ($this->productRequest->can('submit_proposal')) {
            $this->productRequest->apply('submit_proposal');
            $this->productRequest->save();
        }

        $this->assignNextApprover();
        $this->save();

        $handler = new AssignProductProposalCommandHandler;
        $handler->handle(new AssignProductProposalCommand($this));
    }

    /**
     * @throws \Exception
     */
    public function assignNextApprover()
    {
        if ($this->num_approvals <= 0) {
            $this->assigned_to_id = $this->productRequest->requested_by_id;
            $this->assigned_at = Carbon::now();
        } else {
            $approvers = $this->company->settings()->get('product-requests.approvers', null);
            if ($approvers) {
                foreach ($approvers as $approver) {
                    $approver_id = array_get($approver, 'approver_id', false);
                    if (!in_array($approver_id, $this->previousApprovers())) {
                        $this->assigned_to_id = $approver_id;
                        $this->assigned_at = Carbon::now();
                        Event::fire('Insight.ProductProposals.Events.ProductProposalWasAssigned', new ProductProposalWasAssigned($this));
                        return;
                    }
                }
            } else {
                throw new \Exception('Can not assign approver');
            }

        }
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function afterRecalled($myStatefulInstance, $transitionEvent)
    {
        if ($this->productRequest->can('recall_proposal')) {
            $this->productRequest->apply('recall_proposal');
            $this->productRequest->save();
        }
        $this->assigned_to_id = null;
        $this->resetApprovalsCounter();
        $this->save();
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function beforeDelete($myStatefulInstance, $transitionEvent)
    {
        if (in_array($this->getState(), ['APP','APR']) && $this->productRequest->can('delete_active_proposal')) {
            $this->productRequest->apply('delete_active_proposal');
            $this->productRequest->save();
        }

        $this->assigned_to_id = null;
        $this->resetApprovalsCounter();
        $this->save();
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function beforeApprove($myStatefulInstance, $transitionEvent)
    {
        $this->resetAutoApprovalFlag();
    }

    /**
     *
     */
    protected function resetApprovalsCounter()
    {
        $this->num_approvals = 0;
    }

    /**
     *
     */
    protected function resetAutoApprovalFlag()
    {
        $this->auto_approved = false;
        $this->save();
    }


    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function afterApprove($myStatefulInstance, $transitionEvent)
    {
        $this->num_approvals ++;
        $requiredApprovals = $this->numberOfRequiredApprovals();
        if ($this->num_approvals >= $requiredApprovals) {
            $this->apply('final_approve');
            $this->approved_by_id = $this->assigned_to_id;
            $this->assigned_to_id = null;
            $this->approved_at = Carbon::now();
            if($this->productRequest->can('approve')) {
                $this->productRequest->apply('approve');
                $this->productRequest->save();
            }
        } else {
            $this->assignNextApprover();
        }
        $this->save();
    }

    /**
     * @return int
     */
    public function numberOfRequiredApprovals()
    {
        $requireApprovals = 1;
        $approvers = $this->company->settings()->get('product-requests.approvers', null);
        if ($approvers) {
            foreach ($approvers as $approver) {
                if (array_get($approver, 'enabled') && array_get($approver, 'approver_id', false)) {
                    $requireApprovals ++;
                }
            }
        }

        return $requireApprovals;
    }

    /**
     * @param $myStatefulInstance
     * @param $transitionEvent
     * @throws \Exception
     */
    public function afterReject($myStatefulInstance, $transitionEvent)
    {

        if ($this->productRequest->can('reject_proposal')) {
            $this->productRequest->apply('reject_proposal');
        }
        $this->resetApprovalsCounter();
        $this->productRequest->save();
    }

    /**
     * @return mixed
     */
    public function lastTransition()
    {
        $lastTransition = ProductProposalStateTransition::where('product_proposal_id', $this->attributes['id'])->get()->last();

        return $lastTransition;
    }

    /**
     * @return mixed
     */
    public function lastApprover()
    {
        $lastApproval = ProductProposalStateTransition::where('product_proposal_id', $this->attributes['id'])
            ->where('event', 'approve')
            ->get()->last()->user_id;

        return (int) $lastApproval;
    }

    /**
     * @return mixed
     */
    public function previousApprovers()
    {
        $approvers = ProductProposalStateTransition::where('product_proposal_id', $this->attributes['id'])
            ->where('event', 'approve')
            ->lists('user_id');

        return $approvers;
    }

    /**
     * User whom originally created the product proposal
     *
     * @return mixed
     */
    public function createdBy()
    {
        return $this->belongsTo('Insight\Users\User', 'created_by_id');
    }

    /**
     * Company whom this product proposal belongs to
     *
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo('Insight\Companies\Company');
    }

    /**
     * User whom last updated the product proposal
     *
     * @return mixed
     */
    public function updatedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'updated_by_id');
    }

    /**
     * User whom the product proposal is assigned to
     *
     * @return mixed
     */
    public function assignedTo()
    {
        return $this->belongsTo('Insight\Users\User', 'assigned_to_id');
    }

    /**
     * User whom the product proposal is assigned to
     *
     * @return mixed
     */
    public function approvedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'approved_by_id');
    }

    /** Sourcing Request can have many associated comments
     * @return mixed
     */
    public function comments()
    {
        return $this->morphMany('Insight\Comments\Comment', 'commentable');
    }

    /**
     * Associated images for this product proposal
     *
     * @return mixed
     */
    public function images()
    {
        return $this->morphMany('Insight\ProductDefinitions\ProductImage', 'imageable')->where('imageable_id', '<>', '');
    }

    /**
     * Associated images for this product proposal
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
    public function productRequest()
    {
        return $this->belongsTo('Insight\ProductRequests\ProductRequest', 'request_id');
    }

    /**
     * @return mixed
     */
    public function quotation()
    {
        return $this->belongsTo('Insight\Quotations\Quotation');
    }


}
