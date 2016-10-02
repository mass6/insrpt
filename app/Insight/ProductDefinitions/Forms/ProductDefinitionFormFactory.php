<?php namespace Insight\ProductDefinitions\Forms;
use Insight\Companies\Company;
use Insight\Users\User;

/**
 * Insight Client Management Portal:
 * Date: 12/3/14
 * Time: 10:42 AM
 */

class ProductDefinitionFormFactory 
{

    /**
     * @var NewProductDefinitionForm
     */
    private $newProductDefinitionForm;
    /**
     * @var DraftProductDefinitionForm
     */
    private $draftProductDefinitionForm;
    /**
     * @var UpdateProductDefinitionForm
     */
    private $updateProductDefinitionForm;
    /**
     * @var UpdateLimitedProductDefinitionForm
     */
    private $updateLimitedProductDefinitionForm;
    /**
     * @var ProductDefinitionForm
     */
    private $productDefinitionForm;

    /**
     * @var NewSourcingRequestForm
     */
    private $newSourcingRequestForm;

    /**
     * @var UpdateSourcingRequestForm
     */
    private $updateSourcingRequestForm;


    /**
     * @param DraftProductDefinitionForm $draftProductDefinitionForm
     * @param ProductDefinitionForm $productDefinitionForm
     * @param NewProductDefinitionForm $newProductDefinitionForm
     * @param UpdateProductDefinitionForm $updateProductDefinitionForm
     * @param UpdateLimitedProductDefinitionForm $updateLimitedProductDefinitionForm
     * @param NewSourcingRequestForm $newSourcingRequestForm
     * @param UpdateSourcingRequestForm $updateSourcingRequestForm
     */
    public function __construct(
        DraftProductDefinitionForm $draftProductDefinitionForm,
        ProductDefinitionForm $productDefinitionForm,
        NewProductDefinitionForm $newProductDefinitionForm,
        UpdateProductDefinitionForm $updateProductDefinitionForm,
        UpdateLimitedProductDefinitionForm $updateLimitedProductDefinitionForm,
        NewSourcingRequestForm $newSourcingRequestForm,
        UpdateSourcingRequestForm $updateSourcingRequestForm
    )
    {
        $this->newProductDefinitionForm = $newProductDefinitionForm;
        $this->draftProductDefinitionForm = $draftProductDefinitionForm;
        $this->updateProductDefinitionForm = $updateProductDefinitionForm;
        $this->updateLimitedProductDefinitionForm = $updateLimitedProductDefinitionForm;
        $this->productDefinitionForm = $productDefinitionForm;
        $this->newSourcingRequestForm = $newSourcingRequestForm;
        $this->updateSourcingRequestForm = $updateSourcingRequestForm;
    }

    public function make($action, User $user, Company $company)
    {
        switch ($action){
            case "save":
            case "assign-to-customer":
            case "assign-to-supplier":
                return $this->draftProductDefinitionForm;
            case "reviewing":
            case "approval":
            case "approved":
            case "upload":
            case "close":
            case "re-open":
//            case "add":
                if ($customForm = $company->settings()->get('productDefinitions.customValidationForm'))
                {
                    $decorator = new FourCValidationFormDecorator($this->productDefinitionForm);
                    return $decorator->decorate();
                }
                return  $this->productDefinitionForm;
            case "sourcing":
                return $this->newSourcingRequestForm;
            case "sourcing_update":
                return $this->updateSourcingRequestForm;
        }

    }
} 