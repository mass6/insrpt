<?php
namespace Sourcing;
use \FunctionalTester;
use \Codeception\Util\Locator;

class SourcingRequestCommentsCest
{
    protected $user;

    public function _before(FunctionalTester $I)
    {
        $this->user = $I->amLoggedInAsSourcingRequestsUser();
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToAddACommentOnASourcingRequest(FunctionalTester $I)
    {
        $I->am('a user with permissions to sourcing requests');
        $I->wantTo('add a comment on a sourcing request');

        // setup
        $sourcingRequest = $I->haveASourcingRequest();
        $I->amOnRoute('sourcing-requests.edit', ['sourcing-requests' => $sourcingRequest->id]);
        $I->see('Sourcing Request', Locator::combine('h1','h2','h3'));
        $I->dontSee('My test comment', 'p');
        $I->see("{$sourcingRequest->customer_sku}: {$sourcingRequest->customer_product_description}", Locator::combine('h1','h2','h3'));
        $I->see('Remarks', Locator::combine('h1','h2','h3','h4'));

        // execution
        $I->fillField('remarks', 'My test comment');
        $I->click('input[type=submit]');

        // assertions
        $I->seeCurrentRouteIs('sourcing-requests.index');
        $I->see('Sourcing Request was successfully updated.');
        $I->seeRecord('comments', [
            'commentable_id' => $sourcingRequest->id,
            'body' => 'Sourcing Request was updated by ' . $this->user->name() . '.||My test comment'
        ]);
    }


}