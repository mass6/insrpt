<?php

class DatabaseSeeder extends Seeder {

    /**
     * @var array
     */
    protected $tables = ['groups', 'permissions', 'users', 'profiles', 'companies', 'product_definition_statuses',
        'product_definitions', 'settings', 'notifications', 'sourcing_requests', 'product_requests', 'product_request_state_transitions',
        'product_request_lists', 'product_proposals', 'product_proposal_state_transitions', 'product_attachments', 'product_images', 'suppliers'
    ];

    /**
     * @var array
     */
    protected $seeders = [
        'PermissionsTableSeeder',
        'GroupsTableSeeder',
        'CompaniesTableSeeder',
        'SettingsTableSeeder',
        'NotificationsTableSeeder',
        'ProductDefinitionStatusesTableSeeder',
        'ProductDefinitionsTableSeeder',
        'SourcingRequestsTableSeeder',
        'ProductRequestsTableSeeder'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production')
        {
            exit('I just stopped you getting fired. Love Sam');
        }

        // allow for mass assignment
        Eloquent::unguard();

        $this->cleanDatabase();

        $this->seedDatabase();

        $this->enableForeignKeyConstraints(getenv('DB_TYPE'));

    }

    /**
     *
     */
    public function cleanDatabase()
    {
        $this->disableForeignKeyConstraints(getenv('DB_TYPE'));

        foreach ($this->tables as $table)
        {
            DB::table($table)->truncate();
        }
    }

    /**
     *
     */
    public function seedDatabase()
    {
        foreach ($this->seeders as $seed)
        {
            $this->call($seed);
        }
    }

    public function disableForeignKeyConstraints($databaseType)
    {
        $databaseType === 'sqlite'
            ? DB::statement("PRAGMA foreign_keys = OFF")
            : DB::statement("SET foreign_key_checks = 0");
    }

    public function enableForeignKeyConstraints($databaseType)
    {
        $databaseType === 'sqlite'
            ? DB::statement("PRAGMA foreign_keys = ON")
            : DB::statement("SET foreign_key_checks = 1");
    }

}
