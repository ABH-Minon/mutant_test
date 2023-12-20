<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Order;

class CreateOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfOrders = 20;

        for ($i = 0; $i < $numberOfOrders; $i++) {
            $productID = rand(1, 10);
            $product = Product::find($productID);
            $userId = rand(1, 3);
            $quantity = rand(1, 10);

            if ($product) {
                Order::create([
                    'productID' => $productID,
                    'productName' => $product->productName,
                    'productPrice' => $product->productPrice,
                    'userID' => $userId,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
