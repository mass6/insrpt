<?php

use Insight\Settings\Setting;

class SettingsTableSeeder extends Seeder {

	public function run()
	{
        Setting::truncate();

        // notification settings
        Setting::create([
            'name'  => 'system',
            'settings' => '{"site_owner":"1","site_name":"Insight Reporting","dashboards":{"parent_category":"2"},"catalog_requests":{"import_csv_headers":""},"notifications":{"send_portal_data_update_notifications":false}}'
        ]);


        // notification settings
        Setting::create([
            'name'  => 'portal-data-update-notifications',
            'type'  => 'notifications'
        ]);
        // primary cataloguer
        Setting::create([
            'name'  => 'primary-cataloguer',
            'type'  => 'product-cataloguing'
        ]);
        // primary catalogue processor
        Setting::create([
            'name'  => 'primary-provisioner',
            'type'  => 'product-cataloguing'
        ]);
        // primary catalogue processor
        Setting::create([
            'name'  => 'parent-category',
            'type'  => 'category'
        ]);
        // primary catalogue processor
        Setting::create([
            'name'  => 'it-cataloguer',
            'type'  => 'product-cataloguing'
        ]);
        // primary catalogue processor
        Setting::create([
            'name'  => 'insight_company',
            'type'  => 'company'
        ]);
        // primary catalogue processor
        Setting::create([
            'name'  => 'primary-sourcing',
            'type'  => 'product-cataloguing'
        ]);

	}

}