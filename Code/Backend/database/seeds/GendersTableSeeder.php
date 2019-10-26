<?php

use Illuminate\Database\Seeder;

class GendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')->insert([
            ['id' => 1, 'gender_name' => 'Male', 'gender_name_ar' => 'رجل'],
            ['id' => 2, 'gender_name' => 'Female', 'gender_name_ar' => 'أنثى'],
            ['id' => 3, 'gender_name' => 'Not Specified', 'gender_name_ar' => 'غير محدد'],
        ]);
    }
}
