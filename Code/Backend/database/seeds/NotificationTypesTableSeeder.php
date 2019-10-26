<?php

use Illuminate\Database\Seeder;

class NotificationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notification_types')->delete();
        DB::table('notification_types')->insert([
            ['id' => 1, 'notification_type_name' => 'Contact request', 'notification_type_name_ar' => 'جديدة'],
            ['id' => 2, 'notification_type_name' => 'Accepted', 'notification_type_name_ar' => 'مقبولة'],
            ['id' => 3, 'notification_type_name' => 'Rejected', 'notification_type_name_ar' => 'مرفوضة'],
            ['id' => 4, 'notification_type_name' => 'New Rating', 'notification_type_name_ar' => 'تقييم جديد'],
            ['id' => 5, 'notification_type_name' => 'Update Rating', 'notification_type_name_ar' => 'تحديث تقييم']
        ]);
    }
}
