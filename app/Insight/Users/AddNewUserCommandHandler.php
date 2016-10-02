<?php namespace Insight\Users; 
/**
 * Insight Client Management Portal:
 * Date: 7/27/14
 * Time: 4:31 PM
 */

use Insight\Users\Events\UserWasCreated;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Insight\Companies\CompanyRepository;
use Insight\Companies\Company;

class AddNewUserCommandHandler extends UserCommandAbstract
{

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        // Create the User
        try
        {
            $user = Sentry::createUser([
                'email'         =>  $command->email,
                'password'      => $command->password,
                'first_name'    => $command->first_name,
                'last_name'     =>  $command->last_name,
                'company_id'       =>  $command->company_id,
                'activated'     =>  true,
                'permissions'   => $this->serializePermissions($command->permissionsAllowed, $command->permissionsDenied),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'last_login' => date('Y-m-d H:i:s')
            ]);

            $user->profile()->save(new Profile);
            $user->send_email = $command->send_email;
            $user->rawPassword = $command->password;
            if($command->is_primary){
                $company = new CompanyRepository();
                $updateCompany = $company->findById($user->company_id);
                $updateCompany->primary_contact_user_id = $user->id;
                $updateCompany->save();
            }
        }
        catch(Cartalyst\Sentry\Users\UserExistsException $e)
        {
            return $e;
        }

        $this->assignUserToGroups($user, $command->groups);

        $this->subscribeToNotifications($user, $command->notifications);

        $user->raise(new UserWasCreated($user));

        $this->dispatchEventsFor($user);

        return $user;
    }

}