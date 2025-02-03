<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('categories')->insert([
            ['category_name' => 'Elektronik'],
            ['category_name' => 'Ev & Yaşam'],
            ['category_name' => 'Ofis']
        ]);

    }
}
