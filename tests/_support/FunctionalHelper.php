<?php
namespace Codeception\Module;

use Illuminate\Support\Facades\Hash;
use Insight\Companies\Company;
use Insight\Permissions\Group;
use Insight\Sourcing\SourcingRequest;
use Insight\Users\Profile;
use Insight\Users\User;
use Laracasts\TestDummy\Factory as TestDummy;
use Cartalyst\Sentry\Facades\Laravel\Sentry;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class FunctionalHelper extends \Codeception\Module
{

    public function signIn($email = null, $password = null)
    {
        if (!isset($email) && !isset($password)) {
            $email = getenv('TEST_USER_EMAIL');
            $password = getenv('TEST_USER_PASSWORD');
        }

        //$this->haveAnAccount(compact('email', 'password'));

        $I = $this->getModule('Laravel4');

        $I->amOnPage('/login');
        $I->fillField('username', $email);
        $I->fillField('password', $password);
        $I->click('Log In');
    }

    private function have($model, $overrides = [])
    {
        return TestDummy::create($model, $overrides);
    }

    public function haveACustomer()
    {
        //return $customer = Company::where('type', 'customer')->first();
        $customer = Company::create([
            'name' => 'Acme Test Customer',
            'type' => 'customer',
        ]);

        return $customer;
    }

    public function haveAnAccount($overrides = [])
    {
        $company = $this->haveACustomer();
//        return $this->have('Cartalyst\Sentry\Users\Eloquent\User', $overrides);
        $userData = [
            'email'      => 'joecustomer@test.com',
            'password'   => 'secret',
            'first_name' => 'Joe',
            'last_name'  => 'Customer',
            'company_id' => $company->id,
            'activated'  => true,
        ];

        $user = Sentry::createUser(array_merge($userData, $overrides));
        $profile = new Profile();
        $user->profile()->save($profile);
        //$group = Group::where('name', 'Full Permissions Customer')->first();
        //$user->addGroup($group);

        return $user;
    }

    public function amLoggedInAsAdmin()
    {
        $user = Sentry::findUserByID(2);
        Sentry::setUser($user);

        return $user;
    }

    public function amLoggedInAsSourcingRequestsUser()
    {
        $users = Sentry::findAllUsersWithAccess('sourcing-requests');

        $user = array_first($users, function($key, $user) {
            return ! $user->isSuperUser();
        });

        Sentry::setUser($user);

        return $user;
    }

    public function amLoggedInAsProductRequestsUser()
    {
        $users = Sentry::findAllUsers();

        $user = array_first($users, function($key, $user) {
            return ! $user->isSuperUser();
        });

        Sentry::setUser($user);

        return $user;
    }

    public function amLoggedInAsAUser($user)
    {
        Sentry::setUser($user);

        return $user;
    }

    public function amLoggedInAsAUserWithAccessToSettings()
    {
        $users = Sentry::findAllUsers();

        $user = array_first($users, function($key, $user) {
            return (! $user->isSuperUser() && $user->hasAccess('company-settings'));
        });

        Sentry::setUser($user);

        return $user;
    }

    public function haveASourcingRequest()
    {
        return $sourcingRequest = SourcingRequest::first();
    }

    public function haveAccessTo($permissions, $user)
    {
        if (! is_array($permissions)) {
            return $user->permissions = $user->permissions + [$permissions => 1];
        }

        foreach ($permissions as $permission) {
            $this->haveAccessTo($permission, $user);
        }
    }

    public function doNotHaveAccessTo($permission, $user)
    {
        return $user->permissions = array_merge($user->permissions, [$permission => -1]);
    }

}