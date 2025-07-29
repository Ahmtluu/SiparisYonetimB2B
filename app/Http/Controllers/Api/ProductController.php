<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }

    //Herkes erişebilir
    //GET /api/products
    public function index()
    {
        return Product::all();
    }


    //Admin erişebilir
    //POST /api/products
    public function store(Request $request)
    {
        if ($this->user->role === 'admin') {
            
            $validator = Validator::make($request->all(), [
                "name" => ["required", "string"],
                "sku" => ["required", "string"],
                "price" => ["required", "numeric", "min:0"],
                "stock_quantity" => ["required", "numeric", "min:0"]
            ]);

            if ($validator->fails()) {
                return $validator->errors();
            }

            $product = Product::create($request->all());
            return response()->json($product, 201);
        }
        return response()->json(['message' => 'Bu işlemi yapmaya yetkiniz yok.'], 403);
    }

    //Admin erişebilir
    //PUT /api/products/{id}
    public function update(Request $request, $id)
    {
        if ($this->user->role === 'admin') {
            $product = Product::where('id', $id)->get()->first();

            if (!$product) return response()->json(['message' => 'Aradığınız ürün bulunamadı'], 404);

            $validator = Validator::make($request->all(), [
                "name" => ["required", "string"],
                "sku" => ["required", "string"],
                "price" => ["required", "numeric", "min:0"],
                "stock_quantity" => ["required", "numeric", "min:0"]
            ]);

            if ($validator->fails()) {
                return $validator->errors();
            }
            $product->update($request->all());
            return response()->json($product);
        }

        return response()->json(['message' => 'Bu işlemi yapmaya yetkiniz yok.'], 403);
    }

    //Admin erişebilir
    //DELETE /api/products/{id}
    public function destroy($id)
    {
        if ($this->user->role === 'admin') {
            $product = Product::with('user', 'items')->findOrFail($id);
            $product->delete();
            return response()->json(null, 204);
        }
        return response()->json(['message' => 'Bu işlemi yapmaya yetkiniz yok.'], 403);
    }
}
