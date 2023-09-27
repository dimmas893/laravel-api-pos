<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'admin',
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('password')
        ]);
        // for ($p = 1; $p <= 1000; $p++) {
        for ($i = 1; $i <= 10; $i++) {
            Product::create([
                'kode' => 'k-' . $i,
                'name' => 'nama barang ' . $i,
                'price' => 200 . $i,
                'description' => 'deskripsi' . $i,
                'stock' => $i,
                'category_id' => $i,
                'image' =>  $i . '.jpg'
            ]);
        }
        // }
        for ($i = 1; $i <= 10; $i++) {
            Category::create([
                'name' => 'category' . $i,
            ]);
        }
    }
}
