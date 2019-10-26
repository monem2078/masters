<?php

use Illuminate\Database\Seeder;

class RightsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rights')->delete();
        DB::table('rights')->insert([

            ['id' => 1,'right_name' =>'Roles', 'right_name_ar'=>'الادوار', 'module_id'=>2, 'right_url'=>'#/Roles', 'right_order_number'=>1, 'in_menu'=>1, 'icon'=>''],
            ['id' => 2,'right_name' =>'RoleAdd', 'right_name_ar'=>'اضافه دور', 'module_id'=>2, 'right_url'=>'#/Role', 'right_order_number'=>2, 'in_menu'=>0, 'icon'=>''],
            ['id' => 3,'right_name' =>'RoleEdit', 'right_name_ar'=>'تعديل دور', 'module_id'=>2, 'right_url'=>'#/Role/:id', 'right_order_number'=>3, 'in_menu'=>0, 'icon'=>''],


        ]);
    }
}
