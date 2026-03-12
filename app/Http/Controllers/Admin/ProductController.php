<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DrinkDetail;
use App\Models\FoodDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kategori = $request->kategori;

        $products = Product::with(['category', 'fooddetail', 'drinkdetail'])
            ->byCategory($kategori) // pakai scope
            ->paginate(10)
            ->appends(['kategori' => $kategori]);

        return view('admin.pages.product', [
            'title'    => 'Product',
            'products' => $products,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.createproduct', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // ✅ Validasi input utama
        $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'name'               => 'required|string|max:255|unique:products,name',
            'variations'         => 'required|array|min:1',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ambil kategori
        $category = Category::findOrFail($request->category_id);

        // ✅ Simpan produk utama
        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
        ]);

        // ✅ Simpan variasi produk
        foreach ($request->variations as $variation) {
            $imagePath = null;
            if (isset($variation['image']) && $variation['image'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $variation['image']->store('products', 'public');
            }

            if (strtolower($category->name) === 'makanan') {
                // Simpan ke food_details
                FoodDetail::create([
                    'product_id'   => $product->id,
                    'level'        => $variation['level'] ?? null,
                    'price'        => $variation['price'],
                    'image'        => $imagePath,
                    'is_available' => isset($variation['is_available']) ? 1 : 0,
                ]);
            } elseif (strtolower($category->name) === 'minuman') {
                // Simpan ke drink_details
                DrinkDetail::create([
                    'product_id'   => $product->id,
                    'size'         => $variation['size'] ?? null,
                    'price'        => $variation['price'],
                    'image'        => $imagePath,
                    'is_available' => isset($variation['is_available']) ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product); // kirim data JSON
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:products,name,' . $product->id,
            'price'       => 'required|numeric|min:100',
            'size'        => 'nullable|string|in:small,medium,large',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_available'=> 'boolean',
        ]);

        // update data
        $product->update($validated);

        // kalau ada upload gambar
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image' => $path]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupdate',
            'data'    => $product
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus semua detail yang berhubungan dengan produk ini
        if ($product->category->name == 'minuman') {
            $product->drinkdetail()->delete();
        } else {
            $product->fooddetail()->delete();
        }

        // Hapus produk utamanya
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Produk dan semua detailnya berhasil dihapus.');
    }

}
