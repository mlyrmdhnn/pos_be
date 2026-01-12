<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\HttpCache\Store;

class ProductService {

    private function storeImage(UploadedFile $image): string
    {
        if (! $image->isValid()) {
            throw new \RuntimeException('Invalid image upload');
        }

        $extension = $image->extension();

        $fileName = Str::uuid() . '.' . $extension;

        return $image->storeAs(
            'products',
            $fileName,
            'public'
        );
    }

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
            'price' => (int) $data['price'],
            'quantity' => (int) $data['stock'],
            'sold' => (int) '0',
            'category_id' => (int) $data['category'],
            'description' => $data['description'] ?? null,
            'image_path' => $path
        ]);
    }

    public function editProduct(Product $product, array $data, ?UploadedFile $image = null)
    {
        DB::transaction(function () use ($product, $data, $image) {

            $oldImage = $product->image_path;
            $newPath = null;

            if ($image) {
                $newPath = $this->storeImage($image);
                $product->image_path = $newPath;
            }

            $product->name        = $data['name'];
            $product->price       = $data['price'];
            $product->quantity    = $data['quantity'];
            $product->category_id = $data['category'];
            $product->description = $data['description'] ?? null;

            $product->save();

            if (
                $newPath &&
                $oldImage &&
                Storage::disk('public')->exists($oldImage)
            ) {
                Storage::disk('public')->delete($oldImage);
            }
        });

        return $product;
    }

    public function destroy(string $uuid)
    {
        $product = Product::where('uuid', $uuid)->first();

        if($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

    }

    public function detailByUuid(string $uuid): ?Product
    {
        return Product::with(['category'])->where('uuid', $uuid)->first();
    }

    public function byCategory($id) {
        return Product::where('category_id', $id)->latest()->paginate(8);
    }


}