<?php
/**
 * Insight Client Management Portal:
 * Date: 7/26/14
 * Time: 4:44 PM
 */

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Faker\Factory as Faker;
use Faker\Provider\en_GB\PhoneNumber;
use Illuminate\Support\Facades\Log;
use Insight\Companies\Company;
use Insight\Companies\Customer;
use Insight\Companies\Supplier;
use Insight\Permissions\Group;
use Insight\Users\Profile;
use Insight\Users\User;

/**
 * Class CompaniesTableSeeder
 */
class CompaniesTableSeeder extends Seeder
{

    /**
     * Run seed command
     */
    public function run()
    {
        // Default Company
        Company::create([
            'name'                    => '36s',
            'type'                    => 'customer',
            'primary_contact_user_id' => 1,
            'settings'                => '{"portal":{"dataGroup":"","website_code":"","store":""},"design":{"layout":""},"productDefinitions":{"template":"","customValidationForm":"","customImageLabels":{"imageLabel1":"","imageLabel2":"","imageLabel3":"","imageLabel4":""},"customAttachmentLabels":{"attachmentLabel1":"","attachmentLabel2":"","attachmentLabel3":"","attachmentLab":""}},"product-requests":{"approvers":{"approver1":{"approver_id":""},"approver2":{"approver_id":""}},"references":{"enabled":"true"},"reference1":{"enabled":"true","label":""},"reference2":{"enabled":"true","label":""},"reference3":{"enabled":"true","label":""},"reference4":{"enabled":"true","label":""},"procurement-categories":[]}}'
        ]);

        $adminUser = Sentry::createUser([
            'first_name'  => 'Admin',
            'last_name'   => 'User',
            'email'       => 'admin@admin.com',
            'password'    => 'admin',
            'company_id'  => '1',
            'activated'   => true,
            'permissions' => ['superuser' => 1]
        ]);

        $testUser = Sentry::createUser([
            'first_name'  => 'John',
            'last_name'   => 'Doe',
            'email'       => 'johndoe@test.com',
            'password'    => 'testing',
            'company_id'  => '1',
            'activated'   => true,
            'permissions' => ['superuser' => 1]
        ]);

        //delete previous/obsolete profiles
        DB::table('profiles')->truncate();

        $adminUser->profile()->save(new Profile);
        $testUser->profile()->save(new Profile);

        // Find the group using the group id
        $adminGroup = Sentry::findGroupById(1);

        // Assign the group to the user
        $adminUser->addGroup($adminGroup);
        $testUser->addGroup($adminGroup);


        // fake customer companies
        $faker = Faker::create();
        $faker->addProvider(new PhoneNumber($faker));

        foreach (range(1, 9) as $index) {
            $company = Company::create([
                'name'                    => $faker->unique()->company,
                'type'                    => 'customer',
                'primary_contact_user_id' => 1
            ]);

            // attach users to company
            $fullPermissionsCustomerGroup = Group::where('name', 'Full Permissions Customer')->first();
            $limitedPermissionsCustomerGroup = Group::where('name', 'Limited Permissions Customer')->first();

            foreach (range(1, 10) as $index) {
                $user = new User([
                    'first_name' => $faker->firstName,
                    'last_name'  => $faker->lastName,
                    'email'      => $faker->unique()->email(),
                    'password'   => 'secret',
                    'activated'  => true
                ]);

                $company->users()->save($user);
                $group = rand(0, 1) == 1 ? $fullPermissionsCustomerGroup : $limitedPermissionsCustomerGroup;
                $user->addGroup($group);
            }

            $company->primary_contact_user_id = $company->users->first()->id;
            $company->save();

            $numSuppliers = rand(1, 10);
            foreach (range(1, $numSuppliers) as $index) {
                $supplier = new Supplier([
                    'name'            => $faker->unique()->company,
                    'address'         => $faker->address,
                    'email'           => $faker->companyEmail,
                    'website'         => $faker->domainName,
                    'primary_contact' => $faker->name,
                    'telephone1'      => $faker->phoneNumber,
                    'telephone1'      => $faker->phoneNumber,
                    'fax'             => $faker->phoneNumber,
                    'description'     => $faker->paragraph()
                ]);

                $company->associatedSuppliers()->save($supplier);
            }

        }
        // delete previous/obsolete relations
        DB::table('customer_supplier')->truncate();
    }

}