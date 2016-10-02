<?php

use Insight\Permissions\Permission;

class PermissionsTableSeeder extends Seeder
{

    public function run()
    {

        // add superuser permission
        Permission::create(['name' => 'superuser']);

        $crudActions = ['add', 'edit', 'view', 'delete'];

        $crudPermissions = [
            'users',
            'permissions',
            'groups',
            'product-requests',
            'product-proposals',
            'companies',
            'cataloguing.products',
            'sourcing-requests'
        ];

        $viewPermissions = [
            'dashboards',
            'portal.users',
            'portal.orders',
            'portal.contracts',
            'portal.products'
        ];

        foreach ($crudPermissions as $permission) {
            foreach ($crudActions as $action) {
                Permission::create(['name' => $permission . '.' . $action]);
            }
        }

        foreach ($viewPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Misc permissions
        Permission::create(['name' => 'portal.doa']);
        Permission::create(['name' => 'portal.products.export']);
        Permission::create(['name' => 'customers.data']);
        Permission::create(['name' => 'cataloguing.products.edit.full']);
        Permission::create(['name' => 'cataloguing.products.admin']);
        Permission::create(['name' => 'cataloguing.products.catalogue']);
        Permission::create(['name' => 'cataloguing.products.process']);
        Permission::create(['name' => 'company-settings']);

        // Product Requests
        Permission::create(['name' => 'product-requests.create']);
        Permission::create(['name' => 'product-requests.save_draft']);
        Permission::create(['name' => 'product-requests.submit_request']);
        Permission::create(['name' => 'product-requests.delete_draft']);
        Permission::create(['name' => 'product-requests.save_reviewing']);
        Permission::create(['name' => 'product-requests.reassign_to_requester']);
        Permission::create(['name' => 'product-requests.submit_for_sourcing']);
        Permission::create(['name' => 'product-requests.save_sourcing']);
        Permission::create(['name' => 'product-requests.submit_for_pricing']);
        Permission::create(['name' => 'product-requests.create_proposal']);
        Permission::create(['name' => 'product-requests.save_proposing']);
        Permission::create(['name' => 'product-requests.submit_proposal']);
        Permission::create(['name' => 'product-requests.revert_for_review']);
        Permission::create(['name' => 'product-requests.save_approving']);
        Permission::create(['name' => 'product-requests.approve']);
        Permission::create(['name' => 'product-requests.reject']);
        Permission::create(['name' => 'product-requests.close']);

        // Product Requests
        Permission::create(['name' => 'product-proposals.create']);
        Permission::create(['name' => 'product-proposals.save']);
        Permission::create(['name' => 'product-proposals.submit']);
        Permission::create(['name' => 'product-proposals.approve']);
        Permission::create(['name' => 'product-proposals.reject']);
        Permission::create(['name' => 'product-proposals.close']);

        // Product Request Lists;
        Permission::create(['name' => 'product-request-lists.create']);

        // Product Requests
        Permission::create(['name' => 'quotations.edit']);
    }

}