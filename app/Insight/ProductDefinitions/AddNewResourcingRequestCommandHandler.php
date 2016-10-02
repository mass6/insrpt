<?php namespace Insight\ProductDefinitions;
use Insight\Core\CommandBus;
use Insight\ProductDefinitions\Events\ProductDefinitionWasCreated;
use Insight\ProductDefinitions\Events\ProductDefinitionWasAssigned;
use Insight\Comments\AddNewCommentCommand;
use Insight\Companies\CompanyRepository;
use Insight\Settings\Setting;
use Illuminate\Support\Facades\Log;
use Insight\ProductDefinitions\ProductRequestStatus;

class AddNewResourcingRequestCommandHandler extends ProductDefinitionCommandHandlerAbstract{
    public function handle($command){
        try
        {
            $command->code = 'SOURCING_'.$command->user->company->name.'_'.date('Y-m-d H:i:s',time());
            $command->company_id = Setting::where('name', 'insight_company')->pluck('value');
            $command->status = ProductRequestStatus::Review; //Under Catalogue Team Review
            $command->user_id = $command->user->id;
            $command->assigned_user_id  = Setting::where('name', 'primary-sourcing')->pluck('value');
            $command->assigned_by_id = $command->user->id;
            $command->created_at = date('Y-m-d H:i:s',time());
            $command->supplier_id = Setting::where('name', 'insight_supplier_company')->pluck('value');
            // persist the new request
            $product = $this->productDefinitionRepository->add($command);
            $product->save();

        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
            return $e->getMessage();
        }

        // raise and dispatch the events
        $product->raise(new ProductDefinitionWasCreated($product));

        $this->dispatchEventsFor($product);
    }
}