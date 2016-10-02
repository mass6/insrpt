<?php namespace Insight\Users;
use Insight\Users\Events\UserWasDeleted;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;
use Insight\Companies\CompanyRepository;

/**
 * Insight Client Management Portal:
 * Date: 7/29/14
 * Time: 3:41 PM
 */

class DeleteUserCommandHandler implements  CommandHandler
{
    use DispatchableTrait;
    /**
     * @var UserRepository
     */
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        $savedUser = clone $command->user;
        $companyRepository = new CompanyRepository();
        $companyRepository->updatePrimaryContact($command->user->id);
        $this->user->delete($command->user->id);

        $savedUser->raise(new UserWasDeleted($savedUser));

        $this->dispatchEventsFor($savedUser);
    }
}