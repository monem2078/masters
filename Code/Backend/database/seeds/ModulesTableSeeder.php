<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->delete();
        DB::table('modules')->insert([
            ['id' => 1, 'module_name' => 'Control Panel', 'module_name_ar' => 'لوحه التحكم', 'icon'=>''],
            ['id' => 2, 'module_name' => 'Security', 'module_name_ar' => 'الحمايه', 'icon'=>''],

        ]);
    }
}
