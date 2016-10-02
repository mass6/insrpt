<?php

use Insight\Notifications\Notification;

class NotificationsTableSeeder extends Seeder {

	public function run()
	{
        Notification::create([
            'name'  => 'ContractsUpdated'
        ]);
        Notification::create([
            'name'  => 'ProductsUpdated'
        ]);

        // delete previous/obsolete relations
        DB::table('notification_user')->truncate();

	}

}