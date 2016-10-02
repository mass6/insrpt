<?php
namespace CompanySettings;

use Codeception\Util\Locator;
use \FunctionalTester;

class CompanySettingsCest
{

    protected $user;

    public function _before(FunctionalTester $I)
    {
        $user = $I->haveAnAccount();
        $I->amLoggedInAsAUser($user);
        //$I->haveAccessTo('company-settings', $user);
        $this->user = $user;
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    private function dit_has_a_company(FunctionalTester $I)
    {
        $I->am('a customer user');
        $I->wantTo('view my company settings');
        $I->doNotHaveAccessTo('company-settings', $this->user);
        $I->amOnRoute('company-settings.edit', $this->user->company->id);
        $I->see('You do not have the appropriate privileges to view the requested page.');
    }

    public function its_does_not_show_a_link_to_company_settings_if_user_does_not_have_access_permissions(FunctionalTester $I)
    {
        $I->am('a customer without permissions to my company settings');
        $I->wantTo('verify company settings link is not displayed in menu without proper permissions');
        $I->doNotHaveAccessTo('company-settings', $this->user);
        $I->amOnPage('/');
        $I->seeCurrentUrlEquals('/');
        $I->dontSee('Company Settings', 'a');
    }

    public function its_does_not_show_company_settings_if_user_does_not_have_access_permissions(FunctionalTester $I)
    {
        $I->am('a customer without permissions to my company settings');
        $I->wantTo('verify I cannot edit my company settings without proper permissions');
        $I->doNotHaveAccessTo('company-settings', $this->user);

        $I->amOnRoute('company-settings.edit', $this->user->company->id);
        $I->dontSee("Company Settings", Locator::combine('h1', 'h2', 'h3', 'h4', 'h5'));
        $I->see('You do not have the appropriate privileges to view the requested page.');
    }

    public function its_shows_link_to_company_settings_if_user_has_access_permissions(FunctionalTester $I)
    {
        $I->am('a customer with permissions to my company settings');
        $I->wantTo('view my company settings page');
        $I->haveAccessTo('company-settings', $this->user);

        $I->amOnPage('/');
        $I->seeCurrentUrlEquals('/');
        $I->see('Company Settings', 'a');
    }
    public function its_shows_company_settings_if_user_has_access_permissions(FunctionalTester $I)
    {
        $I->am('a customer with permissions to my company settings');
        $I->wantTo('view my company settings page');
        $I->haveAccessTo('company-settings', $this->user);

        $I->amOnRoute('company-settings.edit');
        $I->seeCurrentUrlEquals('/company-settings');
        $I->see("Company Settings", Locator::combine('h1', 'h2', 'h3', 'h4', 'h5'));
    }

    public function it_does_not_show_reference_fields_if_not_enabled_by_admin(FunctionalTester $I)
    {
        $I->am('a customer with permissions to my company settings');
        $I->expectTo('not have access to product reference fields if functionality is not enabled by admin');
        $I->haveAccessTo('company-settings', $this->user);
        $this->disableReferenceFieldsFunctionality();

        $I->amOnRoute('company-settings.edit');
        $I->dontSeeElement('#reference-fields');
    }

    public function it_shows_reference_fields_when_enabled_by_admin(FunctionalTester $I)
    {
        $I->am('a customer with permissions to my company settings');
        $I->expectTo('access product reference fields if functionality is enabled by admin');
        $I->haveAccessTo('company-settings', $this->user);
        $this->enableReferenceFieldsFunctionality();

        $I->amOnRoute('company-settings.edit');
        $I->seeElement('#reference-fields');
    }

    public function its_sets_product_request_reference_fields(FunctionalTester $I)
    {
        $I->am('a customer with permissions to my company settings');
        $I->wantTo('configure reference fields for product requests');
        $I->haveAccessTo('company-settings', $this->user);

        $this->enableReferenceFieldsFunctionality();
        $I->amOnRoute('company-settings.edit');

        $I->checkOption('input[name="settings[product-requests][reference1][enabled]"]');
        $I->checkOption('input[name="settings[product-requests][reference1][required]"]');
        $I->fillField('input[name="settings[product-requests][reference1][label]"]', 'RF1');
        $I->checkOption('input[name="settings[product-requests][reference2][enabled]"]');
        $I->checkOption('input[name="settings[product-requests][reference2][required]"]');
        $I->fillField('input[name="settings[product-requests][reference2][label]"]', 'RF2');
        $I->checkOption('input[name="settings[product-requests][reference3][enabled]"]');
        $I->checkOption('input[name="settings[product-requests][reference3][required]"]');
        $I->fillField('input[name="settings[product-requests][reference3][label]"]', 'RF3');
        $I->checkOption('input[name="settings[product-requests][reference4][enabled]"]');
        $I->checkOption('input[name="settings[product-requests][reference4][required]"]');
        $I->fillField('input[name="settings[product-requests][reference4][label]"]', 'RF4');
        $I->click('input[type=submit]');

        $I->see('Settings have been successfully saved.', 'div');

        $company = $I->grabRecord('companies', ['id' => $this->user->company->id]);
        $settings = json_decode($company->settings);

        $I->assertEquals('true', $settings->{"product-requests"}->reference1->enabled);
        $I->assertEquals('true', $settings->{"product-requests"}->reference1->required);
        $I->assertEquals('RF1', $settings->{"product-requests"}->reference1->label);

        $I->assertEquals('true', $settings->{"product-requests"}->reference2->enabled);
        $I->assertEquals('true', $settings->{"product-requests"}->reference2->required);
        $I->assertEquals('RF2', $settings->{"product-requests"}->reference2->label);

        $I->assertEquals('true', $settings->{"product-requests"}->reference3->enabled);
        $I->assertEquals('true', $settings->{"product-requests"}->reference3->required);
        $I->assertEquals('RF3', $settings->{"product-requests"}->reference3->label);

        $I->assertEquals('true', $settings->{"product-requests"}->reference4->enabled);
        $I->assertEquals('true', $settings->{"product-requests"}->reference4->required);
        $I->assertEquals('RF4', $settings->{"product-requests"}->reference4->label);

    }

    public function it_sets_procurement_categories(FunctionalTester $I)
    {
        $I->am('a customer with permissions to my company settings');
        $I->wantTo('configure reference fields for product requests');
        $I->haveAccessTo('company-settings', $this->user);

        $I->amOnRoute('company-settings.edit');
        $I->seeElement('#procurement_categories');
        $I->fillField('#procurement_categories', "category-one\r\ncategory-two\r\ncategory-three");
        $I->click('input[type=submit]');
        $I->see('Settings have been successfully saved.', 'div');

        $company = $I->grabRecord('companies', ['id' => $this->user->company->id]);
        $settings = json_decode($company->settings);
        $categories = $settings->{"product-requests"}->{"procurement-categories"};

        $I->assertEquals(['category-one', 'category-two', 'category-three'], $categories);
    }

    private function disableReferenceFieldsFunctionality()
    {
        $this->user->company->settings()->set('product-requests.references.enabled', false);

    }

    private function enableReferenceFieldsFunctionality()
    {
        $this->user->company->settings()->set('product-requests.references.enabled', true);
    }
}