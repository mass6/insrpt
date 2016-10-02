<?php namespace Insight\ProductDefinitions;
use Insight\Core\CommandBus;
use Insight\ProductDefinitions\Events\ProductDefinitionWasUpdated;
use Insight\ProductDefinitions\Events\ProductDefinitionWasAssigned;
use Insight\ProductDefinitions\Events\ProductDefinitionWasCompleted;
use Insight\Comments\AddNewCommentCommand;
use Insight\Settings\Setting;
use Insight\Companies\CompanyRepository;
use Illuminate\Support\Facades\Log;
use Insight\ProductDefinitions\ProductRequestStatus;

/**
 * Insight Client Management Portal:
 * Date: 11/7/14
 * Time: 2:17 PM
 */

class UpdateProductDefinitionCommandHandler extends ProductDefinitionCommandHandlerAbstract
{
    use CommandBus;

    protected $wasAssigned = false;
    protected $isCompleted = false;

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        // Create the Company
        try
        {
            // serialize the attributes input array
            if(isset($command->attributes))
                $command->attributes = json_encode($command->attributes);

            // determine the status
            $command->status = $this->setStatus($command->action);

            // update the new request
            //$product = $command->formType === 'full' ? $this->productDefinitionRepository->update($command) : $this->productDefinitionRepository->updateLimited($command);
            $product = $this->productDefinitionRepository->update($command->product, $command);

            // assign the request to designated user
            $product->assigned_user_id = (int)$this->setAssignedUser($command);
            if($this->wasAssigned)
                $product->assigned_by_id = $command->user->id;
            $product->save();

//             determine if request is completed
            $this->isCompleted = $command->action === 'close' ? true : false;

            // add the history comment
            $comment = $this->execute(new AddNewCommentCommand(
                $product,
                $command->user->id,
                $this->compileComment($product, $command)
                . ($command->remarks !== '' ? '||' . $command->remarks : '')
            ));

            // attach images & file attachments
            //$this->attachImage($product, $command->image1);
            //$this->attachImage($product, $command->image2);
            //$this->attachImage($product, $command->image3);
            //$this->attachImage($product, $command->image4);

            //$this->attachAttachments($product, $command->attachments);

        }
        catch (Exception $e)
        {
            return 'Could not update product.';
        }

        // Raise the relevant events
        if($this->isCompleted)
            $product->raise(new ProductDefinitionWasCompleted($product, $command->remarks));
        else if($this->wasAssigned)
            $product->raise(new ProductDefinitionWasAssigned($product, $command->remarks));
        else
            $product->raise(new ProductDefinitionWasUpdated($product));


        $this->dispatchEventsFor($product);

        return $product;
    }

    protected function setStatus($action)
    {
        switch ($action){
            case "save":
                return ProductRequestStatus::Draft;
            case "assign-to-customer":
                return ProductRequestStatus::Draft;
            case "assign-to-supplier":
                return ProductRequestStatus::Draft; // draft
            case "reviewing":
                return ProductRequestStatus::Review; //Under Catalogue Team Review
            case "approval":
                return ProductRequestStatus::Pending; //Pending Approval
            case "approved":
                return ProductRequestStatus::Approved; //Approved
            case "upload":
                return ProductRequestStatus::Upload; //Ready for Upload
            case "close":
                return ProductRequestStatus::Closed; //Closed
            case "re-open":
                return ProductRequestStatus::Draft;
        }
    }


    /**
     * Determine who the request should be assigned to based on request status
     *
     * @param $command
     * @return mixed
     */
    protected function setAssignedUser($command)
    {
        $companyRepository = new CompanyRepository();
        switch ($command->action){
            case "assign-to-customer":
                $this->wasAssigned = true;
                $contactId = $companyRepository->getPrimaryContact($command->company_id);

                if($contactId){
                    return $contactId;
                } else {
                    Log::info('The customer had not been chosen.');
                    return $command->user->id;
                }

            case "assign-to-supplier":
                $this->wasAssigned = true;
                $contactId = $companyRepository->getPrimaryContact($command->supplier_id);

                if($contactId){
                    return $contactId;
                } else {
                    Log::info('The supplier had not been chosen.');
                    return $command->user->id;
                }

            case "reviewing": // Under Catalogue Team Review
                $this->wasAssigned = true;
                return Setting::where('name', 'primary-cataloguer')->pluck('value');

            case "approval": // Pending Approval
                $this->wasAssigned = true;
                $contactId = $companyRepository->getPrimaryContact($command->company_id);

                if($contactId){
                    return $contactId;
                } else {
                    Log::info('The customer had not been chosen.');
                    return $command->user->id;
                }

            case "approved":
                $this->wasAssigned = true;
                return Setting::where('name', 'primary-cataloguer')->pluck('value');

            case "upload":
                $this->wasAssigned = true;
                return Setting::where('name', 'it-cataloguer')->pluck('value');

            case "close":
                $this->wasAssigned = true;
                return $command->product->assigned_user_id;

            case "re-open":
                $this->wasAssigned = true;
                return Setting::where('name', 'primary-cataloguer')->pluck('value');

            default: // save draft
                return $command->product->assigned_user_id;
        }
    }


    protected function compileComment($product, $command)
    {
        $companyRepository = new CompanyRepository();
        switch ($command->action){
            case "save":
//            case "update":
                return 'Request was updated by ' . $product->updatedBy->name() . '.';

            case "assign-to-customer":
                $message = 'Request was updated by ' . $product->updatedBy->name() . '.';
                $contactId = $companyRepository->getPrimaryContact($command->company_id);
                if($contactId) {
                    $message = 'Request assigned to ' . $product->assignedTo->name() . ' by ' . $product->assignedBy->name() . ' for input.';
                }

                return $message;

            case "assign-to-supplier":
                $message = 'Request was updated by ' . $product->updatedBy->name() . '.';
                $contactId = $companyRepository->getPrimaryContact($command->supplier_id);
                if($contactId){
                    $message = 'Request assigned to supplier contact ' . $product->assignedTo->name() . ' by ' . $product->assignedBy->name() . ' for input.';
                }

                return $message;

            case "reviewing":
                return 'Request was submitted to ' . $product->assignedTo->name() . ' by ' . $product->assignedBy->name() . ' for review.';

//            case "process":
//                return 'Request was submitted to ' . $product->assignedTo->name() . ' by ' . $product->assignedBy->name() . ' for processing.';

            case "close":
                return 'Request was completed by ' . $product->updatedBy->name() . '.';

            default:
                return 'Request was updated by ' . $product->updatedBy->name() . '.';
        }
    }

    protected function userWasAssigned($action)
    {
        switch ($action) {
            case 'revert':
                return true;
            case 'submit':
                return true;
            case 'process':
                return true;
            default:
                return false;
        }

    }


    protected function attachImage($product, $image)
    {
        if (! is_null($image))
        {
            ProductImage::create([
                'imageable_id'      =>  $product->id,
                'imageable_type'    =>  get_class($product),
                'image'             =>  $image
            ]);
        }
    }

    /**
     * Persist each images to DB product_images table
     *
     * @param $product
     * @param $images
     */
    protected function attachImages($product, $images)
    {
        if(is_array($images)){
            foreach ($images as $image)
            {
                if (! is_null($image) && ! empty($image))
                {
                    ProductImage::create([
                        'imageable_id'      =>  $product->id,
                        'imageable_type'    =>  get_class($product),
                        'image'             =>  $image
                    ]);
                }
            }
        }
    }

    /**
     * Persist each attachment to DB product_attachments table
     *
     * @param $product
     * @param $attachments
     */
    protected function attachAttachments($product, $attachments)
    {
        if(is_array($attachments)){
            foreach ($attachments as $attachment)
            {
                if (! is_null($attachment) && ! empty($attachments)) {
                    ProductAttachment::create([
                        'attachable_id' => $product->id,
                        'attachable_type' => get_class($product),
                        'attachment' => $attachment
                    ]);
                }
            }
        }
    }
}