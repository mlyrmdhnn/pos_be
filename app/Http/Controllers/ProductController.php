<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product() {
        return response()->json([
            'status' => 'ok',
            'msg' => 'success',
            'data' => Product::latest()->get()
        ]);
    }

    public function productCategory() {
        return response()->json([
            'status' => 'ok',
            'msg' => 'success',
            'data' => ProductCategory::latest()->get()
        ]);
    }
}
