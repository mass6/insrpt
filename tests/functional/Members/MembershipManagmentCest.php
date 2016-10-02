<?php
namespace Members;

use \FunctionalTester;

/**
 * Class MembershipManagmentCest
 * @package Members
 */
class MembershipManagmentCest
{

    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
    }

    /**
     * @param FunctionalTester $I
     */
    public function _after(FunctionalTester $I)
    {
    }

    // tests
    /**
     * View all Users
     * @param FunctionalTester $I
     */
    public function tryToViewAllUsers(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('view all users');

        $I->amOnPage('/admin/users');
        $I->see('Users List');
        $I->seeElement('#datatable');
        $I->see('admin@admin.com');
        $I->see('36s', 'td');
        $I->see('Administrator', 'td');
        $I->see('superuser : allow', 'td');
    }
    /**
     * @param FunctionalTester $I
     */
    public function tryToTestValidationRulesWhenCreatingANewUser(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('test validation rules are enforced when creating a new user');

        $I->amOnPage('/admin/users/create');

        $I->click('input[type=submit]'); // empty input

        $I->seeCurrentRouteIs('admin.users.create');
        $I->seeCurrentUrlEquals('/admin/users/create');
        $I->seeSessionErrorMessage(['email' => 'The email field is required.']);
        $I->seeSessionErrorMessage(['password' => 'The password field is required.']);
        $I->canSee('The email field is required.');
        $I->canSee('The password field is required.');
    }

    /**
     * View all Companies
     * @param FunctionalTester $I
     */
    public function tryToViewAllCompanies(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('view all companies');

        $I->amOnPage('/admin/companies');
        $I->see('Companies');
        $I->seeElement('#datatable');
        $I->see('36s');
        $I->see('customer');

        //TODO: Add additional 'customer group' field to companies grid
        //$I->see('thirtysix_staff');
        //$I->see('Test note.');
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToTestValidationRulesWhenCreatingANewCompany(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('test validation rules are enforced when creating a new company');

        $I->amOnPage('/admin/companies/create');
        $I->fillField('address1_body', 'Test Address');
        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('admin.companies.create');
        $I->seeCurrentUrlEquals('/admin/companies/create');
        $I->seeSessionErrorMessage(['name' => 'The name field is required.']);
        $I->seeSessionErrorMessage(['address1_description' => 'The address1 description field is required when address1 body is present.']);
        $I->canSee('The name field is required.');
        $I->canSee('The address1 description field is required when address1 body is present.');

        //TODO: Add validation for 'type', '
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToCreateANewCompany(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('create a new company');

        $I->amOnPage('/admin/companies/create');
        $I->see('New Company');

        $I->fillField('name', 'New Test Company');
        $I->fillField('type', 'customer');
        $I->selectOption('magento_customer_group_id', 'thirtysix_staff');
        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('admin.companies.index');
        $I->seeCurrentUrlEquals('/admin/companies');
        $I->canSee('Company was successfully created.');
    }
}