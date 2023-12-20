<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class CreateProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'productName' => 'Product 1',
                'productPrice' => '100',
            ],
            [
                'productName' => 'Product 2',
                'productPrice' => '200',
            ],
            [
                'productName' => 'Product 3',
                'productPrice' => '300',
            ],
            [
                'productName' => 'Product 4',
                'productPrice' => '400',
            ],
            [
                'productName' => 'Product 5',
                'productPrice' => '500',
            ],
            [
                'productName' => 'Product 6',
                'productPrice' => '600',
            ],
            [
                'productName' => 'Product 7',
                'productPrice' => '700',
            ],
            [
                'productName' => 'Product 8',
                'productPrice' => '800',
            ],
            [
                'productName' => 'Product 9',
                'productPrice' => '900',
            ],
            [
                'productName' => 'Product 10',
                'productPrice' => '1000',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
