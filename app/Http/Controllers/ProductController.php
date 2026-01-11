<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product() {
        $products = Product::with(['category'])->latest()->paginate(8);
        return response()->json([
          $products
        ]);
    }

    public function productCategory() {
        return response()->json([
            'status' => 'ok',
            'msg' => 'success',
            'data' => ProductCategory::latest()->get()
        ]);
    }

    public function create(Request $request, ProductService $service) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|numeric',
            'category' => 'required',
            'stock' => 'required|numeric',
            'image' => 'required|image'
        ]);

        $image = $request->file('image');

        if (!$image) {
            return response()->json([
                'image gaada'
            ]);
        }


        $product = $service->createProduct($validated, $request->file('image'));

        return response()->json([
            'status' => 'ok',
            'msg' => 'success create product',
            'data' => $product
        ],201);
    }

    public function edit(Request $request, ProductService $sercice) {

    }
}
