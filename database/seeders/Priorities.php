<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class Priorities extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('priorities')->insert([
            ['title' => 'baja'],
            ['title' => 'media'],
            ['title' => 'alta'],
        ]);
    }
}
