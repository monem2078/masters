<?php

use Illuminate\Database\Seeder;

class PlatformsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('platforms')->insert([
            ['id' => 1, 'platform_name' => 'Android', 'platform_name_ar' => 'Android'],
            ['id' => 2, 'platform_name' => 'Ios', 'platform_name_ar' => 'Ios'],
        ]);
    }
}
