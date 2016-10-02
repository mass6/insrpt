<?php

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Faker\Factory as Faker;
use Insight\ProductRequests\ProductRequest;
use Insight\Users\User;

/**
 * Class ProductRequestsTableSeeder
 */
class ProductRequestsTableSeeder extends Seeder
{

    /**
     * Run seed command
     */
    public function run()
    {

        Eloquent::unguard();
        $faker = Faker::create();
        $users = User::where('company_id', '>', 1)->get();
        $uom = ['Each', 'Box', 'Carton', 'Dozen', '20Kg', 'Pack'];

        foreach ($users as $user) {

            Sentry::setUser($user);

            $numRequests = $faker->numberBetween(4, 10);

            for ($i = 0; $i < $numRequests; $i ++) {

                $request = new ProductRequest([
                    'company_id'               => $user->company->id,
                    'product_description'      => ucwords(str_replace('.', '', $faker->sentence($faker->numberBetween(1, 4)))),
                    'sku'                      => $faker->ean8,
                    'uom'                      => $faker->randomElement(['Each', 'Pack', 'Carton']),
                    'category'                 => $faker->randomElement(['cleaning', 'construction', 'mep']),
                    'purchase_recurrence'      => $faker->randomElement(['AHC', 'MON', 'ANN']),
                    'volume_requested'         => $faker->randomDigitNotNull,
                    'current_supplier'         => $faker->company,
                    'current_supplier_contact' => $faker->address,
                    'current_price'            => $faker->numberBetween($min = 100, $max = 9900),
                    'current_price_currency'   => 'AED',
                    'reference1'               => $faker->phoneNumber,
                    'reference2'               => $faker->userName,
                    'reference3'               => $faker->company,
                    'reference4'               => $faker->colorName,
                    'remarks'                  => $faker->paragraph(2),
                    'state'                    => $faker->randomElement(['DRA']),
                    'created_by_id'            => $user->id,
                    'updated_by_id'            => $user->id,
                ]);

                $user->productRequests()->save($request);


            }

        }


    }

}