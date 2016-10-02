<?php

$I = new FunctionalTester($scenario);

$I->am('a system administrator');
$I->amLoggedInAsAdmin();
$I->wantTo('view the dashboard and verify data is populated');

$I->amOnPage('/');

$I->click('Dashboard');

$I->canSeeCurrentRouteIs('dashboards.home');
$I->canSee('products ordered');