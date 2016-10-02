<?php namespace Insight\ProductDefinitions;
use Insight\Core\CommandBus;
use Insight\ProductDefinitions\Events\ProductDefinitionWasCreated;
use Insight\ProductDefinitions\Events\ProductDefinitionWasAssigned;
use Insight\Comments\AddNewCommentCommand;
use Insight\Companies\CompanyRepository;
use Insight\Settings\Setting;
use Illuminate\Support\Facades\Log;
use Insight\ProductDefinitions\ProductRequestStatus;

/**
 * Insight Client Management Portal:
 * Date: 11/7/14
 * Time: 2:17 PM
 */

class AddNewProductDefinitionCommandHandler extends ProductDefinitionCommandHandlerAbstract
{
    use CommandBus;

    protected $wasAssigned = false;

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
            // serialize the attributes in the input array
            if(isset($command->attributes))
                $command->attributes = json_encode($command->attributes);

            // determine the status
            $command->status = $this->setStatus($command->action);
            // persist the new request
            $product = $this->productDefinitionRepository->create($command);

            // assign the request to designated user
            $product->assigned_user_id = (int)$this->setAssignedUser($command);
            $product->save();

            // add the history comment
            $comment = $this->execute(new AddNewCommentCommand(
                $product,
                $product->user_id,
                $this->compileComment($product, $command)
            ));

            // save the attached images
            //$this->attachImages($product, [$command->image1, $command->image2, $command->image3, $command->image4]);

            // save the attached file attachments
            //$this->attachAttachments($product, $command->attachments);


            //dd($command);

//            if(!isset($command->attributes) && (int)$command->company_id === 2)
//            {
//                $command->attributes = '{"Brand":"","HS Code":"","Barcode Number":"","Country of Manufacture":"","Lead Time":"","Ingredients":"","Calories":"","Calories From Fat":"","Total Fat":"","Saturated Fat":"","Trans Fat":"","Cholesterol":"","Sodium":"","Total Carbohydrates":"","Dietary Fiber":"","Sugars":"","Protein":"","Vitamin A":"","Vitamin C":"","Calcium":"","Iron":"","Packaging":"","Packaging Type":"","Shelf Life":"","Storage Condition":"","Weight Case Net":"","Weight Case Gross":"","Weight Individual Net":"","Weight Individual Gross":"","Weight Individual Drain":""}';
//            }



            //dd($command);
            // create the product request
            //$product = $this->productDefinitionRepository->create($command);

            // add the history comment


            // process images and attachments

        }
        catch (Exception $e)
        {
            return 'Could not create product.';
        }

        // raise and dispatch the events
        $product->raise(new ProductDefinitionWasCreated($product));
        if($this->wasAssigned)
            $product->raise(new ProductDefinitionWasAssigned($product, $command->remarks));

        $this->dispatchEventsFor($product);
    }

    protected function setStatus($action)
    {
        switch ($action){
            case "save":
                return ProductRequestStatus::Draft; //draft
            case "assign-to-customer":
                return ProductRequestStatus::Draft; //draft
            case "assign-to-supplier":
                return ProductRequestStatus::Draft; // draft
            case "reviewing":
                return ProductRequestStatus::Review; // submitted

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
                $contactId = null;

                if($command->company_id){
                    $contactId = $companyRepository->getPrimaryContact($command->company_id);
                }

                if($contactId){
                    return $contactId;
                } else{
                    Log::info('The customer had not been chosen.');
                    return $command->user->id;
                }

            case "assign-to-supplier":
                $this->wasAssigned = true;
                $contactId = null;

                if($command->supplier_id){
                    $contactId = $companyRepository->getPrimaryContact($command->supplier_id);
                }

                if($contactId){
                    return $contactId;
                } else{
                    Log::info('The supplier had not been chosen.');
                    return $command->user->id;
                }

            case "reviewing": // submitted
                $this->wasAssigned = true;
                return Setting::where('name', 'primary-cataloguer')->pluck('value');

            default: //draft
                return $command->user->id;
        }
    }

    protected function compileComment($product, $command)
    {
        $companyRepository = new CompanyRepository();
        switch ($command->action){
            case "save":
                return 'Request draft created by ' . $product->createdBy->name() . '.';
            case "assign-to-customer":
                $message = 'Request created by ' . $product->createdBy->name();
                if($command->company_id){
                    $contactId = $companyRepository->getPrimaryContact($command->company_id);
                    if($contactId)
                        $message .= ' and assigned to ' . $product->customer->primaryContact->name() . ' for input';
                }

                return $message.'.';
            case "assign-to-supplier":
                $message = 'Request created by ' . $product->createdBy->name();
                if($command->supplier_id){
                    $contactId = $companyRepository->getPrimaryContact($command->supplier_id);
                    if($contactId)
                        $message .= ' and assigned to supplier contact ' . $product->assignedTo->name() . ' for input';
                }
                 return $message.'.';
            case "reviewing":
                return 'Request created by ' . $product->createdBy->name() . ' and submitted to ' . $product->assignedTo->name() . ' for review.';

            default:
                return 'Request created.';
        }
    }


    /**
     * Persist each images to DB product_images table
     *
     * @param $product
     * @param $images
     */
    protected function attachImages($product, Array $images)
    {
        foreach ($images as $image)
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
    }

//    protected function attachImage($product, $image)
//    {
//        if (! is_null($image))
//        {
//            ProductImage::create([
//                'imageable_id'      =>  $product->id,
//                'imageable_type'    =>  get_class($product),
//                'image'             =>  $image
//            ]);
//        }
//    }

    /**
     * Persist each attachment to DB product_attachments table
     *
     * @param $product
     * @param $attachments
     */
    protected function attachAttachments($product, $attachments)
    {
        foreach ($attachments as $attachment)
        {
            if (! is_null($attachment)) {
                ProductAttachment::create([
                    'attachable_id' => $product->id,
                    'attachable_type' => get_class($product),
                    'attachment' => $attachment
                ]);
            }
        }
    }
}