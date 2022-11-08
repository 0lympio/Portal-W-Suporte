<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'Treinamentos', 'slug' => 'treinamentos', 'icon' => '<i class="fa fa-book-open"></i>','isMenu' => true, 'status' => 1]);
        Category::create(['name' => 'Procedimentos', 'slug' => 'procedimentos', 'icon' => '<i class="fa fa-list-check"></i>','isMenu' => true, 'status' => 1]);
        Category::create(['name' => 'Feed de Noticias', 'slug' => 'feed-de-noticias', 'icon' => '<i class="fa fa-newspaper"></i>','isMenu' => true, 'status' => 1]);
        Category::create(['name' => 'Fique ON', 'slug' => 'fique-on', 'icon' => '<i class="fa-solid fa-circle-question"></i>', 'isMenu' => false, 'status' => 1]);
    }
}
