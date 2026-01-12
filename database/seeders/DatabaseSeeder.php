<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Database\Factories\ProductCategoryFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Mulya Ramadhan',
            'username' => 'mungskie',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone' => '081295096247',
        ]);

        User::create([
            'name' => 'testing',
            'username' => 'manager',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'phone' => '12121212'
        ]);
        User::create([
            'name' => 'casher',
            'username' => 'casher',
            'password' => Hash::make('password'),
            'role' => 'casher',
            'phone' => '09090909'
        ]);

        ProductCategory::factory()->count(3)->create();
        Product::factory()->count(60)->create();
    }

}
