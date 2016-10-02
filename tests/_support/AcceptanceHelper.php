<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I


class AcceptanceHelper extends \Codeception\Module
{
    public function amLoggedInAsAdmin()
    {
        $I = $this;

        $I->amOnPage('/login');
        $I->fillField('username', 'johndoe@test.com');
        $I->fillField('password', 'testing');
        $I->click('Log In');
    }


}