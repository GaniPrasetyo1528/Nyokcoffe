<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DrinkDetail;
use App\Models\FoodDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255|unique:products,name',
            'price'         => 'required|numeric|min:100',
            'size'          => 'nullable|in:small,medium,large',
            'level'         => 'nullable|integer|min:0|max:10',
            'image'         => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'is_available'  => 'boolean',
        ]);

        // buat produk utama
        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']),
        ]);

        // upload file
        $imagePath = $request->file('image')->store('products', 'public');

        // cek kategori
        $category = Category::find($validated['category_id']);

        if ($category && strtolower($category->name) === 'minuman') {
            DrinkDetail::create([
                'product_id'   => $product->id,
                'is_available' => $request->has('is_available'),
                'size'         => ucfirst($validated['size']), // simpan Small/Medium/Large
                'price'        => $validated['price'],
                'image'        => $imagePath,
            ]);
        } elseif ($category && strtolower($category->name) === 'makanan') {
            FoodDetail::create([
                'product_id'   => $product->id,
                'is_available' => $request->has('is_available'),
                'level'        => $validated['level'],
                'price'        => $validated['price'],
                'image'        => $imagePath,
            ]);
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
    public function destroy(Product $product)
    {
        // kalau ada image, bisa sekalian hapus dari storage
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus!');
    }
}
