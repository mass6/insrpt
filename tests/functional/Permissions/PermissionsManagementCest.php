<?php
use \FunctionalTester;

class PermissionsManagementCest
{

    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToCreateANewPermission(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('create a new permission');

        $I->amOnPage('/admin/permissions/create');
        $I->canSee('New Permission');

        $I->fillField('name', 'test.permission');
        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('admin.permissions.index');
        $I->seeCurrentUrlEquals('/admin/permissions');
        $I->canSee('Permission "test.permission" was successfully created.');
    }

    public function tryToCreateANewPermissionsGroup(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('create a new permissions group');

        $I->amOnPage('/admin/groups/create');
        $I->canSee('New Group');
        $I->canSee('Allowed Permissions');
        $I->seeElement('form');
        $I->seeElement('option', ['value' => 'users.add']);

        $I->fillField('name', 'Test Group');
        $I->selectOption('select', ['users.add', 'users.view']);
        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('admin.groups.index');
        $I->seeCurrentUrlEquals('/admin/groups');
        $I->canSee('Group was successfully created.');
    }

    public function tryToEditAPermissionsGroup(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('edit an existing permissions group');

        $I->amOnPage("/admin/groups");
        $I->see('Limited Permissions Customer', 'td');
        $I->dontSee('quotations.edit', 'td');

        $group = $I->grabRecord('groups', ['name' => 'Limited Permissions Customer']);
        $I->amOnPage("/admin/groups/{$group->id}/edit");
        $I->canSee('Permissions Group Administration');
        $I->canSee('Allowed Permissions');
        $I->seeElement('form');
        $I->seeElement('option', ['value' => 'quotations.edit']);
        $I->selectOption('select', ['quotations.edit']);
        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('admin.groups.index');
        $I->seeCurrentUrlEquals('/admin/groups');
        $I->see('Limited Permissions Customer', 'td');
        $I->see('quotations.edit', 'td');
        $I->canSee('Group was successfully updated.');
    }

    public function tryToTestValidationRulesWhenCreatingAPermission(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('test validation rules are enforced when creating a permission');

        $I->amOnPage('/admin/permissions/create');
        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('admin.permissions.create');
        $I->seeCurrentUrlEquals('/admin/permissions/create');
        $I->seeSessionErrorMessage(['name' => 'The name field is required.']);
        $I->canSee('The name field is required.');
    }

    public function tryToTestValidationRulesWhenCreatingAPermissionsGroup(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('test validation rules are enforced when creating a permissions group');

        $I->amOnPage('/admin/groups/create');
        // test empty input
        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('admin.groups.create');
        $I->seeCurrentUrlEquals('/admin/groups/create');
        $I->seeSessionErrorMessage(['name' => 'The name field is required.']);
        $I->canSee('The name field is required.');
    }

    public function tryToViewAllPermissionsGroups(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('view all permissions groups');

        $I->amOnPage('/admin/groups');
        $I->see('Permission Groups');
        $I->seeElement('#datatable');
        $I->see('Administrator');
        $I->see('Limited Permissions Customer');
        $I->see('Full Permissions Customer');

    }

    public function tryToViewAllPermissions(FunctionalTester $I)
    {
        $I->am('a system administrator');
        $I->wantTo('view all permissions');

        $I->amOnPage('/admin/permissions');
        $I->see('Permissions');
        $I->seeElement('#datatable');
        $I->see('cataloguing.products.add');
        $I->see('companies.delete');

    }
}