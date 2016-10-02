<?php 
$I = new FunctionalTester($scenario);
$I->wantTo('perform actions and see result');
$I->amLoggedInAsAdmin();

$I->amOnRoute('catalogue.product-definitions.create');
$I->see('Select the customer.');
$customerOption = $I->grabTextFrom('form select[name=company_id] option:nth-child(3)');
codecept_debug($customerOption);
$I->selectOption("form select[name=company_id]", $customerOption);
$I->click('Go!');
$I->see($customerOption);
$I->click('Add new supplier');
$I->see('New Supplier Company');
$I->fillField('name', 'New Supplier');
$I->fillField('first_name', 'Supplier1');
$I->fillField('last_name', 'Test');
$I->fillField('email', 'newsupplier@test.com');
$I->click('input[type=submit]');
$I->see($customerOption);
$I->see('New Supplier');