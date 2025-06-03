<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'assassino cappuchino',
                'description' => 'Secret assassin',
                'price' => 49.99,
                'image' => '/images/products/assassinocappuchino.jpg'
            ],
            [
                'name' => 'ballerina cappuchina',
                'description' => 'dance service',
                'price' => 129.99,
                'image' => '/images/products/ballerinacappuchina.jpg'
            ],
            [
                'name' => 'bombardiro crocodilo',
                'description' => 'bomb a country',
                'price' => 79.99,
                'image' => '/images/products/bombardirocrocodilo.jpg'
            ],
            [
                'name' => 'brrbrr batapim',
                'description' => 'toe cleaning',
                'price' => 299.99,
                'image' => '/images/products/brrbrrbatapim.jpg'
            ],
            [
                'name' => 'piccionemachina',
                'description' => 'personal mechanic',
                'price' => 199.99,
                'image' => '/images/products/piccionemachina.jpg'
            ],
            [
                'name' => 'Tung tung tung sahur',
                'description' => 'wood ',
                'price' => 29.99,
                'image' => '/images/products/sahur.jpg'
            ],
            [
                'name' => 'Trallalero trallalla',
                'description' => 'swimming tutorials',
                'price' => 89.99,
                'image' => '/images/products/trallalera.jpg'
            ],
           
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 