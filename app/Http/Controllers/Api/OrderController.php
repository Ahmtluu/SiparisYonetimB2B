<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }

    //Admin tüm siparişleri, müşteri sadece kendi siparişlerini görebilmeli
    //GET /api/orders
    public function index(Request $request)
    {

        if ($this->user->role === 'admin') {
            // Admin tüm siparişleri görebilir
            return Order::with('user', 'items')->get();
        }

        // Müşteri sadece kendi siparişlerini görebilir
        return Order::with('items')->where('user_id', $this->user->id)->get();
    }


    //Yalnızca yetkili kullanıcı erişebilmeli
    //GET /api/orders/{id}
    public function show($id)
    {
        $order = Order::with('user', 'items')->findOrFail($id);

        // Admin her siparişi görebilir, müşteri sadece kendi siparişini
        if ($this->user->role !== 'admin' && $order->user_id !== $this->user->id) {
            return response()->json(['message' => 'Bu siparişi görüntülemeye yetkiniz yok.'], 403);
        }

        return $order;
    }

    //Müşteri yeni sipariş oluşturmalı
    //POST /api/orders
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "user_id" => ["required", "numeric"],
            "status" => ["required", "string"],
            "total_price" => ["required", "numeric", "min:0"],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => 'pending',
            'total_price' => 0
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $quantity = $item['quantity'];
            $unitPrice = $product->price;

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice
            ]);

            $total += $quantity * $unitPrice;
        }

        $order->update(['total_price' => $total]);

        return response()->json($order->load(['items.product']), 201);
    }
}
