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
            'name' => 'required|max:25',
            'price' => 'required',
            'stock' => 'required',
            'category' => 'required',
            'description' => 'required',
            'image' => 'required'
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

    public function detail($prd, ProductService $service)
    {
        $product = $service->detailByUuid($prd);

        if(!$product)
        {
            return response()->json([
                'status' => 'failed',
                'msg' => 'product not found'
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'msg' => 'success',
            'data' => $product
        ], 200);
    }

    public function destroy(Request $request, ProductService $service)
    {
        $service->destroy($request->uuid);

        return response()->json([
            'status' => 'ok',
            'msg' => 'success delete product'
        ],200);
    }


    public function edit(Request $request, ProductService $service)
    {

        $request->validate([
            'id' => 'required',
            'name' => 'string',
            'price' => 'required',
            'quantity' => 'required',
            'category' => 'required',
            'image' => 'nullable'
        ]);

        $product = Product::findOrFail($request->id);

        $service->editProduct(
            $product,
            $request->all(),
            $request->file('image')
        );

        return response()->json([
            'message' => 'Product updated',
            'data'    => $product
        ]);
    }

    public function byCategory(Request $request, ProductService $service)
    {
        // return response()->json([$id]);
        return response()->json([
            'id' => $request->id
        ]);
    }

}
