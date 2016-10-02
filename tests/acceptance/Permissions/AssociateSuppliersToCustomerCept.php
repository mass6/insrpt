<?php

$I = new AcceptanceTester($scenario);
TestCommons::logMeIn($I);

$I->am('a system administrator');
$I->wantTo('associate suppliers to a customer');

$I->amOnPage("/admin/companies");

// Loop through companies until the first customer is found
$type = 'supplier';
$x = 1;
while ( $type == 'supplier') {
    $x++;
    $I->amOnPage("/admin/companies/{$x}/edit");
    $type = $I->grabValueFrom('input[name=type]');
}

$I->amOnPage("/admin/companies/{$x}/edit");
$I->waitForText('Edit Company', 10, 'h2');
$I->canSee('Associated Suppliers', 'label');

// select 36s from the list of available suppliers
$I->see('36s', ".ms-selectable");
$I->dontSee('36s', ".ms-selection");
$I->executeJS('return $("#1-selectable").click()');
$I->see('36s', ".ms-selection");

// submit form and confirm
$I->wait('1');
$I->click('input[type=submit]');
$I->seeCurrentUrlEquals('/admin/companies');
$I->see('Company was successfully updated.');



