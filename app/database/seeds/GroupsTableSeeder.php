<?php
use Insight\Permissions\Group;
/**
 * Insight Client Management Portal:
 * Date: 7/26/14
 * Time: 4:44 PM
 */


class GroupsTableSeeder extends Seeder {

    public function run()
    {

        Sentry::createGroup(array(
            'name'        => 'Administrator',
            'permissions' => array(
                'admin' => 1,
                'users' => 1,
            ),
        ));

        $portalData = [
            'dashboards' => 1,
            'portal.users' => 1,
            'portal.orders' => 1,
            'portal.contracts' => 1,
            'portal.products' => 1,
        ];

        $productCataloguing = [
            'cataloguing.products.add' => 1,
            'cataloguing.products.edit' => 1,
            'cataloguing.products.view'  => 1,
            'cataloguing.products.delete'  => 1,
        ];

        $productSourcing = [
            'sourcing-requests' => 1,
        ];

        $specialPermissions = [
            'portal.products.export' => 1,
        ];

        Sentry::createGroup(array(
            'name'        => 'Limited Permissions Customer',
            'permissions' => $portalData,
            )
        );

        Sentry::createGroup(array(
            'name'        => 'Full Permissions Customer',
            'permissions' => array_merge($portalData, $productCataloguing, $productSourcing, $specialPermissions),
            )
        );

        // delete previous/obsolete relations
        DB::table('users_groups')->truncate();


    }

}