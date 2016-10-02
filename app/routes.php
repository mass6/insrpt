<?php

use Illuminate\Support\Facades\Queue;

//Event::listen('illuminate.query', function($query)
//{
//    var_dump($query);s
//});

// Authentication routes
Route::get('login', [
    'as'   => 'login_path',
    'uses' => 'SessionsController@create'
]);
Route::post('login', [
    'as'   => 'login_path',
    'uses' => 'SessionsController@store'
]);
Route::get('logout', [
    'as'   => 'logout_path',
    'uses' => 'SessionsController@destroy'
]);

Route::get('forgot-password', ['as' => 'password.forgot', 'uses' => 'PasswordsController@forgotPassword']);
Route::post('forgot-password', ['as' => 'password.forgot', 'uses' => 'PasswordsController@sendResetLink']);
Route::get('reset-password/{token}', ['as' => 'password.edit', 'uses' => 'PasswordsController@edit']);
Route::patch('password-update/{user}', ['as' => 'password.update', 'uses' => 'PasswordsController@update']);
Route::patch('password-verify-update/{user}/{token}', ['as' => 'password.verify_update', 'uses' => 'PasswordsController@verifyAndUpdate']);


Route::get('/insight-admin', 'SessionsController@adminCreate');
Route::post('/insight-admin', 'SessionsController@adminStore');

Route::post('queue/receive', function() {
    return Queue::marshal();
});

// API Routes
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function()
{
    Route::post('mandrill/webhook', 'MandrillWebhooksController@webhook');

});

// Member routes
Route::group(array('before' => 'auth'), function () {

    // Home page
    Route::get('/', [
        'as'   => 'home',
        'uses' => 'PagesController@home'
    ]);

    /**
     * Company Settings
     */
    Route::get('company-settings', ['as' => 'company-settings.edit', 'uses' => 'CompanySettingsController@edit']);
    Route::patch('company-settings/{company}', ['as' => 'company-settings.update', 'uses' => 'CompanySettingsController@update']);
    Route::get('company-settings/suppliers', 'CompanySettingsController@suppliers');
    Route::resource('companies/{company}/suppliers', 'SuppliersController');


    /**
     * Profile
     */
    Route::resource('profiles', 'ProfilesController', ['only' => ['index', 'show', 'update']]);


    /**
     * Dashboards
     */
    Route::get('dashboard', ['as' => 'dashboards.home', 'uses' => 'DashboardsController@home']);

    /**
     * Portal
     */
    Route::get('portal/users', ['as' => 'portal.users', 'uses' => 'Portal\PortalManagementController@getUsers']);
    Route::get('portal/contracts/{customer_name?}', ['as' => 'portal.contracts', 'uses' => 'Portal\PortalManagementController@getContracts']);
    Route::get('portal/products/{customer_name?}', ['as' => 'portal.products', 'uses' => 'Portal\ProductsController@getProducts']);
    Route::get('portal/products-filter/{customer_id?}', ['as' => 'portal.products-filter', 'uses' => 'Portal\ProductsController@getProductsFilter']);
    Route::get('portal/doa', ['as' => 'portal.doa', 'uses' => 'Portal\PortalManagementController@getDoa']);
    Route::get('portal/approval-statistics', ['as' => 'portal.approval-statistics', 'uses' => 'Portal\OrdersController@getApprovalStatistics']);
    Route::get('portal/orders/approvals', ['as' => 'portal.orders.pending-approval', 'uses' => 'Portal\OrdersController@getOrdersPendingApproval']);
    Route::get('portal/orders/search', ['as' => 'portal.orders.search', 'uses' => 'Portal\OrdersController@searchRouter']);
    Route::get('portal/orders/search/{searchTerm}', ['as' => 'portal.orders.search_term', 'uses' => 'Portal\OrdersController@searchOrder']);
    Route::get('portal/orders/details/{id}', ['as' => 'portal.orders.details', 'uses' => 'Portal\OrdersController@getOrderDetails']);
    Route::get('portal/orders/print/{id}', ['as' => 'portal.orders.print', 'uses' => 'Portal\OrdersController@printOrder']);
    Route::get('portal/orders/custom', ['as' => 'portal.orders.custom', 'uses' => 'Portal\OrdersController@getCustomOrderReport']);
    Route::get('portal/orders/today/{customerGroup?}/{contractGroup?}', ['as' => 'portal.orders.today', 'uses' => 'Portal\OrdersController@getOrdersApprovedToday']);
    Route::get('portal/orders/yesterday/{customerGroup?}/{contractGroup?}', ['as' => 'portal.orders.yesterday', 'uses' => 'Portal\OrdersController@getOrdersApprovedYesterday']);
    Route::get('portal/orders/this-month/{customerGroup?}/{contractGroup?}', ['as' => 'portal.orders.this-month', 'uses' => 'Portal\OrdersController@getOrdersApprovedThisMonth']);
    Route::get('portal/orders/third-party-this-month/{customerGroup?}/{contractGroup?}', ['as' => 'portal.orders.third-party-this-month', 'uses' => 'Portal\OrdersController@getThirdPartyOrdersApprovedThisMonth']);
    Route::get('portal/orders/last-month/{customerGroup?}/{contractGroup?}', ['as' => 'portal.orders.last-month', 'uses' => 'Portal\OrdersController@getOrdersApprovedLastMonth']);
    Route::get('portal/orders/ytd/{customerGroup?}/{contractGroup?}', ['as' => 'portal.orders.ytd', 'uses' => 'Portal\OrdersController@getOrdersApprovedYearToDate']);
    Route::get('portal/orders', function () {
        return Redirect::route('portal.orders.today');
    });
    // Portal Deliveries
    Route::get('portal/deliveries', ['as' => 'portal.deliveries.index', 'uses' => 'Portal\DeliveriesController@index']);
    Route::get('portal/deliveries/{delivery}', ['as' => 'portal.deliveries.show', 'uses' => 'Portal\DeliveriesController@show']);
    Route::get('portal/deliveries/{delivery}/print', ['as' => 'portal.deliveries.print', 'uses' => 'Portal\DeliveriesController@printDelivery']);

    // Portal Invoices
    //Route::get('portal/invoices', ['as' => 'portal.invoices.index', 'uses' => 'Portal\InvoicesController@index']);
    Route::get('portal/invoices/{invoice}', ['as' => 'portal.invoices.show', 'uses' => 'Portal\InvoicesController@show']);
    Route::get('portal/invoices/{invoice}/print', ['as' => 'portal.invoices.print', 'uses' => 'Portal\InvoicesController@printInvoice']);


    Route::get('portal/report/products-ordered', ['as' => 'portal.report.products-ordered', 'uses' => 'Portal\ProductsController@getProductsOrderedReport']);
    Route::get('portal/report/products-custom-order-lines', ['as' => 'portal.report.products-custom-order-lines', 'uses' => 'Portal\OrdersController@getCustomOrderLinesReport']);

    // Proposals
    Route::get('product-requests/{request_id}/proposals/create/{quotation?}', ['as' => 'product-proposals.create', 'uses' => 'ProductProposalsController@create']);
    Route::get('product-requests/{request_id}/proposals/{id}/edit', ['as' => 'product-proposals.edit', 'uses' => 'ProductProposalsController@edit']);
    Route::get('product-requests/{request_id}/proposals/{id}', ['as' => 'product-proposals.show', 'uses' => 'ProductProposalsController@show']);
    Route::post('product-requests/{request_id}/proposals', ['as' => 'product-proposals.store', 'uses' => 'ProductProposalsController@store']);
    Route::get('product-proposals/my-approvals', ['as' => 'product-proposals.my-approvals', 'uses' => 'ProductProposalsController@getUserApprovals']);
    Route::post('product-proposals/apply-transition', ['as' => 'product-proposals.apply-transition', 'uses' => 'ProductProposalsController@applyTransition']);
    Route::get('product-proposals/workbench', ['as' => 'product-proposals.workbench', 'uses' => 'ProductProposalsController@workbench']);
    Route::patch('product-proposals/{id}', ['as' => 'product-proposals.update', 'uses' => 'ProductProposalsController@update']);
    Route::get('product-proposals', ['as' => 'product-proposals.index', 'uses' => 'ProductProposalsController@index']);
    Route::patch('product-proposals/{id}/approvals', ['as' => 'product-proposals.approvals', 'uses' => 'ProductProposalsController@approval']);

    // attachments
    Route::post('product-requests/attachments', ['as'=>'product-requests.attachments.store', 'uses' => 'ProductRequestsController@storeAttachments']);
    Route::delete('product-requests/attachments/{type}/{id}', ['as'=>'product-requests.attachments.delete', 'uses' => 'ProductRequestsController@deleteAttachments']);
    Route::post('product-proposals/attachments', ['as'=>'product-proposals.attachments.store', 'uses' => 'ProductProposalsController@storeAttachments']);
    Route::delete('product-proposals/attachments/{type}/{id}', ['as'=>'product-proposals.attachments.delete', 'uses' => 'ProductProposalsController@deleteAttachments']);

    /**
     * Product Requests
     */
    Route::get('product-requests/quotation-request/{quotation_request}', ['as' => 'product-requests.find-by-quotation-request', 'uses' => 'ProductRequestsController@findByQuotationRequest']);
    Route::post('product-requests/apply-transition', ['as' => 'product-requests.apply-transition', 'uses' => 'ProductRequestsController@applyTransition']);
    Route::get('product-requests/get-product-requests', 'ProductRequestsController@getProductRequests');
    Route::resource('product-requests', 'ProductRequestsController');

    // product lists
    Route::post('product-request-lists/confirm', ['as' => 'product-request-lists.confirm', 'uses' => 'ProductRequestListsController@confirm']);
    Route::get('product-request-lists/create', ['as' => 'product-request-lists.create', 'uses' => 'ProductRequestListsController@create']);
    Route::get('product-request-lists/sample', ['as' => 'product-request-lists.download-sample', 'uses' => 'ProductRequestListsController@sample']);
    Route::post('product-request-lists', ['as' => 'product-request-lists.store', 'uses' => 'ProductRequestListsController@store']);
    Route::get('product-request-lists/{id}', ['as' => 'product-request-lists.show', 'uses' => 'ProductRequestListsController@show']);
    Route::get('product-request-lists', ['as' => 'product-request-lists.index', 'uses' => 'ProductRequestListsController@index']);

    // Quotations
    Route::resource('quotations', 'QuotationsController', ['except' => ['destroy']]);
    Route::post('quotations/{quotation}', 'QuotationsController@destroy');
    Route::get('quotations/get-by-quotation-request/{quotation_request}', 'QuotationsController@getByQuotationRequest');
    Route::resource('quotation-drafts', 'QuotationDraftsController');

    // Quotation Requests
    Route::get('quotation-requests/duplicate/{quotation_request_id}', ['as' => 'quotation-requests.duplicate', 'uses' => 'QuotationRequestsController@duplicate']);
    Route::resource('quotation-requests', 'QuotationRequestsController');

    /**
     * Product Definitions
     */

    Route::group(array('prefix' => 'catalogue'), function () {
        Route::get('product-definitions/myrequests', ['as' => 'catalogue.product-definitions.queue', 'uses' => 'ProductDefinitionsController@getQueue']);
        Route::get('product-definitions/completed', ['as' => 'catalogue.product-definitions.completed', 'uses' => 'ProductDefinitionsController@getCompleted']);
        Route::get('product-definitions/export', ['as' => 'catalogue.product-definitions.export', 'uses' => 'ProductDefinitionsController@export']);
        Route::get('product-definitions/import', ['as' => 'catalogue.product-definitions.import', 'uses' => 'ProductDefinitionsController@import']);
        Route::post('product-definitions/import_attributes', ['as' => 'catalogue.product-definitions.import_attributes', 'uses' => 'ProductDefinitionsController@editAttributes']);
        Route::get('product-definitions/download/{filter}/{format}', ['as' => 'catalogue.product-definitions.download', 'uses' => 'ProductDefinitionsController@download']);
        Route::get('product-definitions/download_csv/{filter}/{format}', ['as' => 'catalogue.product-definitions.download_csv', 'uses' => 'ProductDefinitionsController@downloadCSV']);
        Route::resource('product-definitions', 'ProductDefinitionsController');
        Route::get('cataloguing/suppliers/{cid}', array(
            'as'   => 'catalogue.suppliers',
            'uses' => 'ProductDefinitionsController@getSuppliers'
        ));
        Route::get('cataloguing/supplier-users/{cid}/{sid}', array(
            'as'   => 'catalogue.supplier-users',
            'uses' => 'ProductDefinitionsController@getAssignableSupplierUsers'
        ));
        Route::get('cataloguing/customer-users/{id}', array(
            'as'   => 'catalogue.customer-users',
            'uses' => 'ProductDefinitionsController@getAssignableCustomerUsers'
        ));
        Route::post('product-definitions/add_resourcing_request', ['as' => 'catalogue.product-definitions.add_resourcing_request', 'uses' => 'ProductDefinitionsController@addResourcingRequest']);
        Route::get('cataloguing/product-definitions/view/{company_id}', ['as' => 'catalogue.product-definitions.index.company_id', 'uses' => 'ProductDefinitionsController@index']);
    });

    /**
     * Attachments
     */
    Route::get('images/{productdefinition}/{id}/delete', 'ProductDefinitionsController@detachImage');
    Route::get('attachments/{productdefinition}/{id}/delete', 'ProductDefinitionsController@detachAttachment');

    /*
     * Sourcing Requests
     */
    Route::get('sourcing-requests/my-requests', ['as' => 'sourcing-requests.my-requests', 'uses' => 'SourcingRequestsController@myRequests']);
    Route::resource('sourcing-requests', 'SourcingRequestsController');
    Route::post('sourcing-requests/import', ['as' => 'sourcing-requests.import.store', 'uses' => 'SourcingRequestsController@confirmImport']);
    Route::get('sourcing-requests/import/create', ['as' => 'sourcing-requests.import.create', 'uses' => 'SourcingRequestsController@createImport']);
    Route::post('sourcing-requests/import/process', ['as' => 'sourcing-requests.import.process', 'uses' => 'SourcingRequestsController@processImport']);

});

/**
 * Admin
 */
// Admin routes
Route::group(array('prefix' => 'admin', 'before' => 'auth|admin'), function () {
    Route::resource('companies', 'Admin\CompaniesController');
    Route::post('companies/{company}/suppliers/copy-from-master-list', ['as' => 'admin.companies.copy_from_master_list', 'uses' => 'SuppliersController@copyFromMasterList']);
    Route::get('companies/ajax/get-formatting-partial', 'Admin\CompaniesController@getFormattingPartial');
    Route::resource('users', 'Admin\UsersController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::resource('groups', 'Admin\GroupsController');
    Route::resource('settings', 'Admin\SettingsController');
    Route::get('/', [
        'as'   => 'admin.index',
        'uses' => 'Admin\AdminController@index'
    ]);
});
Route::get('companies/supplier/{id}', array(
    'as'   => 'admin.companies.create_supplier',
    'uses' => 'Admin\CompaniesController@createSupplier'
));
Route::post('companies/add', array(
    'as'   => 'admin.companies.add_supplier',
    'uses' => 'Admin\CompaniesController@addSupplier'
));