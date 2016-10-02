<?php

use Insight\Companies\Company;

class TestCommons
{

    public static $username = 'johndoe@test.com';
    public static $password = 'testing';

    public static function logMeIn($I)
    {
        $I->amOnPage('/login');
        $I->fillField('username', self::$username);
        $I->fillField('password', self::$password);
        $I->click('Log In');
        $I->waitForText('Welcome', 10, 'h1');

    }

    public static function grabFirstCustomer()
    {
        return Company::where('type', 'customer')->first();
    }
}
 