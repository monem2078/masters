<?php

use Illuminate\Database\Seeder;

class RequestStatusTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('request_status_types')->delete();
        DB::table('request_status_types')->insert([
            ['id' => 1, 'request_status_type_name' => 'New', 'request_status_type_name_ar' => 'جديدة'],
            ['id' => 2, 'request_status_type_name' => 'Accepted', 'request_status_type_name_ar' => 'مقبولة'],
            ['id' => 3, 'request_status_type_name' => 'Rejected', 'request_status_type_name_ar' => 'مرفوضة']
        ]);
    }
}
