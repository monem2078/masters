<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        DB::table('roles')->insert([
            ['id' => 1, 'role_name' => 'Admin', 'role_name_ar' => 'ادمن'],
            ['id' => 2, 'role_name' => 'Client', 'role_name_ar' => 'عميل']
        ]);
    }
}
