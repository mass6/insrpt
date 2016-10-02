<?php

use Faker\Factory as Faker;
use Insight\Companies\Company;
use Insight\Sourcing\SourcingRequest;

/**
 * Class SourcingItemsTableSeeder
 */
class SourcingRequestsTableSeeder extends Seeder
{

    /**
     * Run seed command
     */
    public function run()
    {

        Eloquent::unguard();
        $faker = Faker::create();
        $customers = Company::whereType('customer')->get();
        $uom = ['Each', 'Box', 'Carton', 'Dozen', '20Kg', 'Pack'];

        foreach ($customers as $customer) {
            $numBatches = $faker->numberBetween(1,4);

            for ($b = 0; $b < $numBatches; $b++) {
                $batchRef = $faker->randomNumber(6);
                $numItems = $faker->numberBetween(4, 10);
                $received = $faker->dateTimeThisMonth($max = 'now');
                $createdById = array_rand([1, 2], 1) + 1;

                for ($i = 0; $i < $numItems; $i ++) {

                    $isUpdated = $faker->boolean();
                    $status = ['ASS', 'SRC', 'PRI'];
                    $isClosed = $faker->boolean(30);
                    $closed_reason = ['COM', 'DUP'];

                    $request = new SourcingRequest([
                        'batch'                        => $batchRef,
                        'received_on'                  => $received,
                        'customer_sku'                 => $faker->ean8,
                        'customer_product_description' => ucwords(str_replace('.', '', $faker->sentence($faker->numberBetween(1, 4)))),
                        'customer_uom'                 => $uom[array_rand($uom, 1)],
                        'customer_price'               => $faker->numberBetween(4, 100) * 100,
                        'customer_price_currency'      => 'AED',
                        'tss_sku'                      => $faker->ean8,
                        'tss_product_name'             => ucwords(str_replace('.', '', $faker->sentence($faker->numberBetween(1, 4)))),
                        'tss_uom'                      => $uom[array_rand($uom, 1)],
                        'tss_buy_price'                => $faker->numberBetween(4, 100) * 100,
                        'tss_buy_price_currency'       => 'AED',
                        'supplier_name'                => $faker->company,
                        'tss_sell_price'               => $faker->numberBetween(4, 100) * 100,
                        'tss_sell_price_currency'      => 'AED',
                        'tss_buy_price_margin'         => $faker->numberBetween(4, 100) * 100,
                        'tss_sell_price_margin'        => $faker->numberBetween(4, 100) * 100,
                        'customer_sell_price_margin'   => $faker->numberBetween(4, 100) * 100,
                        'created_by_id'                => $createdById,
                        'updated_by_id'                => $isUpdated ? 2 : $createdById,
                        'status'                       => $isClosed ? 'CLS' : ($isUpdated ? $status[array_rand($status, 1)] : 'ASS'),
                        'assigned_to_id'               => $faker->boolean(80) ? array_rand([1, 2], 1) + 1 : null,
                        'closed_at'                    => $isClosed == true ? $faker->dateTimeThisMonth() : null,
                        'reason_closed'                => $isClosed == true ? $closed_reason[array_rand($closed_reason)] : null,
                    ]);

                    $customer->sourcingRequests()->save($request);
                }

            }

        }


    }

    private function getRandom($type)
    {
        $categories = [
            'Produce',
            'Pantry',
            'Bread & Bakery',
            'Beverages',
            'Frozen',
            'Household',
        ];
        $uom = [
            'Each',
            'Box',
            'Carton',
            'Dozen',
            '20Kg',
            'Pack',
        ];

        $type = $$type;

        return $type[rand(0, count($type) - 1)];
    }

}