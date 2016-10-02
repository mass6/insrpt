<?php

$I = new FunctionalTester($scenario);

$I->am('a registered user');
$I->wantTo('login to the application');

$I->amOnPage('/login');
$I->fillField('username', 'johndoe@test.com');
$I->fillField('password', 'testing');
$I->click('Log In');

$I->amOnPage('/');
$I->see('Welcome to 36S Insight');
$I->seeInSession('company');
