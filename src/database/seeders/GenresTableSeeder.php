<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('genres')->insert([
            ['genre_name' => '寿司', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['genre_name' => '焼肉', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['genre_name' => '居酒屋', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['genre_name' => 'イタリアン', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['genre_name' => 'ラーメン', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
