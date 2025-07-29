<?php

// tests/Feature/ProductTest.php
namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Sanctum::actingAs($admin, ['*']);

        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'sku' => 'TP001',
            'price' => 99.99,
            'stock_quantity' => 10,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Test Product']);
    }

    public function test_customer_cannot_create_product(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        Sanctum::actingAs($customer, ['*']);

        $response = $this->postJson('/api/products', [
            'name' => 'Unauthorized Product',
            'sku' => 'UP001',
            'price' => 50,
            'stock_quantity' => 5,
        ]);

        $response->assertStatus(403);
    }

}
