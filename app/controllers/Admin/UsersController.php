<?php namespace Admin;

use Insight\Companies\CompanyRepository;
use Insight\Core\CommandBus;
use Insight\Notifications\NotificationRepository;
use Insight\Permissions\GroupRepository;
use Insight\Permissions\PermissionRepository;
use Insight\Users\AddNewUserCommand;
use Insight\Users\DeleteUserCommand;
use Insight\Users\Forms\NewUserForm;
use Insight\Users\Forms\UpdateUserForm;
use Insight\Users\UpdateUserCommand;
use Insight\Users\UserRepositoryInterface;
use Laracasts\Flash\Flash;
use View, Input, Redirect;

class UsersController extends AdminBaseController {

    use CommandBus;

    /**
     * @var \Insight\Users\UserRepository
     */
    private $userRepository;

    /**
     * @var \Insight\Users\Forms\NewUserForm
     */
    private $newUserForm;

    /**
     * @var \Insight\Users\Forms\UpdateUserForm
     */
    private $updateUserForm;

    /**
     * @var \Insight\Permissions\GroupRepository
     */
    private $groupRepository;

    /**
     * @var \Insight\Permissions\PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var NotificationRepository
     */
    private $notificationRepository;


    public function __construct(UserRepositoryInterface $userRepository, GroupRepository $groupRepository, CompanyRepository $companyRepository, PermissionRepository $permissionRepository,
                                NewUserForm $newUserForm, UpdateUserForm $updateUserForm, NotificationRepository $notificationRepository)
    {
        $this->userRepository = $userRepository;
        $this->newUserForm = $newUserForm;
        $this->updateUserForm = $updateUserForm;
        $this->groupRepository = $groupRepository;
        $this->permissionRepository = $permissionRepository;
        $this->companyRepository = $companyRepository;
        $this->notificationRepository = $notificationRepository;

        parent::__construct();
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// get all users
        $users = $this->userRepository->getAll();

        return View::make('admin.users.index', compact('users'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $companies = $this->companyRepository->getList();
        $permissions = $this->permissionRepository->getList();
        $allowedPermissionsDiff = $permissions;
        $deniedPermissionsDiff = $permissions;
        $groups = $this->groupRepository->getList();
        $unSubscribedNotifications = $this->notificationRepository->getList();
        $is_created = true;
        return View::make('admin.users.create', compact('companies',
            'allowedPermissionsDiff', 'deniedPermissionsDiff', 'groups', 'unSubscribedNotifications', 'is_created'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        // Validate form
        $inputs = Input::all();
        $is_primary = false;
        if(isset($inputs['primary_contact'])){
            $is_primary = true;
        }
        $this->newUserForm->validate($inputs);

        extract(Input::only('first_name', 'last_name', 'email', 'password', 'company_id', 'send_email'));
        $permissionsAllowed = Input::get('permissions_allowed', []);
        $permissionsDenied = Input::get('permissions_denied', []);
        $notifications = Input::get('notifications', []);
        $groups = Input::get('groups', []);

        // Create the new user
        try
        {
            $this->execute(new AddNewUserCommand($first_name, $last_name, $email, $password, $company_id,
                $send_email, $permissionsAllowed, $permissionsDenied, $groups, $is_primary, $notifications));
        }
        catch (\Exception $e)
        {
            Flash::error($e->getMessage());
            return Redirect::back();
        }

        Flash::success('User was successfully created.');
        return Redirect::route('admin.users.index');

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = $this->userRepository->find($id);
        return View::make('admin.users.show', compact('user'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $user = $this->userRepository->find($id);

        $companies = $this->companyRepository->getList();
        $allPermissions = $this->permissionRepository->getList();
        $allowedPermissions = $this->userRepository->getAllowedPermissions($user);
        $deniedPermissions = $this->userRepository->getDeniedPermissions($user);
        $allNotifications = $this->notificationRepository->getList();
        $subscribedNotifications = $this->userRepository->getSubscribedNotifications($user);

        $allowedPermissionsDiff = array_diff($allPermissions, $allowedPermissions);
        $deniedPermissionsDiff = array_diff($allPermissions, $deniedPermissions);
        $unSubscribedNotifications = array_diff($allNotifications, $subscribedNotifications);

        $allGroups = $this->groupRepository->getList();
        $userGroups = $this->userRepository->getAssignedGroups($user);
        $groups = array_diff($allGroups, $userGroups);

        return View::make('admin.users.edit', compact(
            'user', 'companies', 'allowedPermissions', 'allowedPermissionsDiff', 'deniedPermissions', 'deniedPermissionsDiff', 'groups', 'userGroups', 'unSubscribedNotifications', 'subscribedNotifications'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        // Validate form
        $this->updateUserForm->validate(Input::all());

        extract(Input::only('first_name', 'last_name', 'email', 'password', 'company_id', 'send_email'));
        $permissionsAllowed = Input::get('permissions_allowed', []);
        $permissionsDenied = Input::get('permissions_denied', []);
        $groups = Input::get('groups', []);
        $notifications = Input::get('notifications', []);

        // Update the new user
        try
        {
            $update = $this->execute(new UpdateUserCommand($id, $first_name, $last_name, $email, $password, $company_id,
                $send_email, $permissionsAllowed, $permissionsDenied, $groups, $notifications));
        }
        catch (\Exception $e)
        {
            Flash::error($e->getMessage());
            return Redirect::back();
        }
        if(!$update){
            //redirect to edit user page if user is a primary contact
            return Redirect::route('admin.users.edit',array('id'=>$id))->with('is_updated',$update);
        }
        Flash::success('User was successfully updated.');
        return Redirect::route('admin.users.index');

    }


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $user = $this->userRepository->find($id);
        //get_class($user);
		$this->execute(new DeleteUserCommand($user));

        Flash::success('User was successfully deleted.');
        return Redirect::route('admin.users.index');
	}


}
