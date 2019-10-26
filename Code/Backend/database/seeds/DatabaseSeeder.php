<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SectionsAppSeeder::class);
    }
}

class SectionsAppSeeder extends Seeder
{

    public function run()
    {

        $this->call(RolesTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(RightsTableSeeder::class);
        $this->call(NotificationTypesTableSeeder::class);
        $this->call(RequestStatusTypeTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(GendersTableSeeder::class);
        $this->call(PlatformsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}