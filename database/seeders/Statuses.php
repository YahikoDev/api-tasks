<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class Statuses extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            ['title' => 'pendiente'],
            ['title' => 'en progreso'],
            ['title' => 'completada'],
        ]);
    }
}
