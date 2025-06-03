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
                'name' => 'Mario',
                'description' => 'The main hero of the Mushroom Kingdom',
                'price' => 49.99,
                'image' => '/images/products/mario.jpg'
            ],
            [
                'name' => 'Luigi',
                'description' => 'Marios taller brother',
                'price' => 129.99,
                'image' => '/images/products/luigi.jpg'
            ],
            [
                'name' => 'Princess Peach',
                'description' => 'The ruler of the Mushroom Kingdom',
                'price' => 79.99,
                'image' => '/images/products/peach.jpg'
            ],
            [
                'name' => 'Bowser',
                'description' => 'King of the Koopas',
                'price' => 299.99,
                'image' => '/images/products/bowser.jpg'
            ],
            [
                'name' => 'Yoshi',
                'description' => 'A friendly dinosaur',
                'price' => 199.99,
                'image' => '/images/products/yoshi.jpg'
            ],
            [
                'name' => 'Toad',
                'description' => 'A loyal servant of Princess Peach',
                'price' => 29.99,
                'image' => '/images/products/toad.jpg'
            ],
            [
                'name' => 'Wario',
                'description' => 'Marios greedy rival',
                'price' => 89.99,
                'image' => '/images/products/wario.jpg'
            ],
            [
                'name' => 'Kirby',
                'description' => 'Fatass jigglypuff',
                'price' => 69.99,
                'image' => '/images/products/kirby.jpg'
            ],
            [
                'name' => 'Gen',
                'description' => 'minu isand gen',
                'price' => 999.99,
                'image' => '/images/products/gen.jpg'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 