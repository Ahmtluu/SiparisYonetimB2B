<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Kullanıcı ve ürün üret
    $users = User::factory(5)->create();
    $products = Product::factory(20)->create();


     foreach ($users as $user) {
        $orders = Order::factory(rand(1, 3))->create(['user_id' => $user->id]);

        foreach ($orders as $order) {
            $items = [];

            $total = 0;
            // Her siparişe 1-5 ürün ata
            foreach ($products->random(rand(1, 5)) as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->price;

                $items[] = new OrderItem([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ]);

                $total += $quantity * $unitPrice;
            }

            $order->items()->saveMany($items);
            $order->update(['total_price' => $total]);
        }
    }
}
}