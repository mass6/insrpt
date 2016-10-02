<?php namespace Insight\Listeners; 
/**
 * Insight Client Management Portal:
 * Date: 8/16/14
 * Time: 4:50 PM
 */
use Insight\Mailers\ProductDefinitionsMailer;
use Insight\ProductDefinitions\Events\ProductDefinitionWasAssigned;
use Insight\ProductDefinitions\Events\ProductDefinitionWasCompleted;


class ProductDefinitionsNotifier extends EventListener
{
    /**
     * @var \Insight\Mailers\VerificationMailer
     */
    private $mailer;



    /**
     * @param ProductDefinitionsMailer $mailer
     */
    public function __construct(ProductDefinitionsMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function whenProductDefinitionWasAssigned(ProductDefinitionWasAssigned $event)
    {
        $product = $event->productDefinition;
        $emailRecipient = $product->assignedTo->email;
        $assignedUser = $product->assignedTo->name();
        $assignedBy = $product->assignedBy->name();
        $assignedByCompany = $product->assignedBy->company->name;
        $customerName = $product->customer->name;
        $remarks = $event->remarks;

        $data = [
            'product' => $product->toArray(),
            'assignedUser' => $assignedUser,
            'assignedBy' => $assignedBy,
            'assignedByCompany' => $assignedByCompany,
            'customerName' => $customerName,
            'remarks'   => $remarks
        ];

        $this->mailer->sendRequestWasAssignedTo($emailRecipient, $data);

    }

    public function whenProductDefinitionWasCompleted(ProductDefinitionWasCompleted $event)
    {
        $product = $event->productDefinition;
        $emailRecipients[] = $product->createdBy->email;
        $emailRecipients[] = $product->cataloguer()->email;
        $updatedBy = $product->updatedBy->name();
        $customerName = $product->customer->name;
        $remarks = $event->remarks;


        $data = [
            'product' => $product,
            'updatedBy' => $updatedBy,
            'customerName' => $customerName,
            'remarks'   => $remarks
        ];

        $this->mailer->sendRequestWasCompletedTo(array_unique($emailRecipients), $data);

    }
}