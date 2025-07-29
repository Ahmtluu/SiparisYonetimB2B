<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_customer_can_create_an_order()
    {
        // 1. Müşteri oluştur
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        // 2. Ürünleri oluştur
        $product1 = Product::factory()->create(['price' => 100, 'stock_quantity' => 10]);
        $product2 = Product::factory()->create(['price' => 200, 'stock_quantity' => 5]);

        // 3. Giriş yap
        Sanctum::actingAs($customer, ['*']);

        // 4. Sipariş verisi
        $orderData = [
            'user_id'=>$customer->id,
            'status'=>'pending',
            'total_price'=>0,
            'items' => [
                ['product_id' => $product1->id, 'quantity' => 2],
                ['product_id' => $product2->id, 'quantity' => 1],
            ]
        ];

        // 5. API isteği gönder
        $response = $this->postJson('/api/orders', $orderData);

        // 6. Doğrulamalar
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'total_price',
                     'items' => [
                         ['product_id', 'quantity', 'unit_price']
                     ]
                 ]);

        // Veritabanı kontrolü
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
        ]);

        $this->assertDatabaseCount('order_items', 2);
    }
}
