<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'asc')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nameProduct' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0', // Tambahkan validasi harga
        ]);

        Product::create([
            'name' => $request->nameProduct,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price, // Tambahkan harga
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nameProduct' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0', // Tambahkan validasi harga
        ]);

        $product->update([
            'name' => $request->nameProduct,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price, // Tambahkan harga
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function qrcode(Product $product)
    {
        // QR code sekarang akan berisi UUID produk yang unik dan aman.
        $productUuid = $product->uuid;

        // Generate QR code
        $qrCode = QrCode::size(250)->generate($productUuid);

        return view('products.qrcode', compact('product', 'qrCode'));
    }
}