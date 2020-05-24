<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Categories are seeded automatically in the BookSeeder
//        DB::table('categories')->insert([
//            'name' => 'Romans',
//        ]);
//
//        DB::table('categories')->insert([
//            'name' => 'Thrillers',
//        ]);
//
//        DB::table('categories')->insert([
//            'name' => 'Kinderboeken',
//        ]);
    }
}
