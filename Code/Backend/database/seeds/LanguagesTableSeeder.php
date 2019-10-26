<?php

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('languages')->insert([
            ['id' => 1, 'language_name' => 'English', 'language_name_ar' => 'الانجليزية'],
            ['id' => 2, 'language_name' => 'Arabic', 'language_name_ar' => 'العربية']
        ]);
    }
}
