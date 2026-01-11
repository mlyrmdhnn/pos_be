<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService {
    public function createProduct(array $data, ?UploadedFile $image) : Product
    {
        $path = $image ? $this->storeImage($image) : null;

        $uuid = '';
        do{
            $uuid = 'PRD-' . Str::uuid();
        } while (Product::where('uuid', $uuid)->first());

        return Product::create([
            'uuid' => $uuid,
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['stock'],
            'category_id' => $data['category'],
            'description' => $data['description'] ?? null,
            'image_path' => $path
        ]);

    }

    private function storeImage(UploadedFile $image) : string
    {

        $fileName = '';
        do{
            $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();
        }
        while(Product::where('image_path', $fileName)->first());


        return $image->storeAs(
            'products',
            $fileName,
            'public'
        );
    }

    public function editProduct(array $data, UploadedFile $image)
    {

    }

}