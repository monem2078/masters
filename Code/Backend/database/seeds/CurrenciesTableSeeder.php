<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            ['id' => 1, 'currency_name' => 'Dollar', 'currency_name_ar' => 'دولار' , 'symbol' => '$'],
            ['id' => 2, 'currency_name' => 'Emirate Dirham', 'currency_name_ar' => 'درهم اماراتى' , 'symbol' => 'AED'],
        ]);
    }
}
