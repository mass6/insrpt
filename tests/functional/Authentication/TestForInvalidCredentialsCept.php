<?php

$I = new FunctionalTester($scenario);

$I->am('a registered user');
$I->wantTo('test for invalid credentials');

$I->amOnPage('/login');
$I->fillField('username', 'invalid@example.com');
$I->fillField('password', 'password');
$I->click('Log In');

$I->amOnPage('/login');
$I->see('Invalid login');
