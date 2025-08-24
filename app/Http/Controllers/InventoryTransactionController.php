<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = InventoryTransaction::with('product')->latest()->paginate(15);
        return view('inventory.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('inventory.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

        // Gunakan transaction database untuk memastikan konsistensi data
        DB::transaction(function () use ($validated, $request) {
            // Buat transaksi inventory
            $transaction = InventoryTransaction::create([
                'product_id' => $validated['product_id'],
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'],
                'transaction_date' => $validated['transaction_date'], 
            ]);

            // Update stok produk
            $product = Product::find($validated['product_id']);
            
            if ($validated['type'] === 'in') {
                $product->stock += $validated['quantity'];
            } else {
                // Validasi stok cukup untuk transaksi keluar
                if ($product->stock < $validated['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk transaksi keluar');
                }
                $product->stock -= $validated['quantity'];
            }
            
            $product->save();
        });

        return redirect()->route('inventory.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $products = Product::orderBy('name')->get();
        return view('inventory.edit', compact('transaction', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        
        // Validasi data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

        // Gunakan transaction database untuk memastikan konsistensi data
        DB::transaction(function () use ($validated, $transaction) {
            // Simpan quantity lama untuk revert stok
            $oldQuantity = $transaction->quantity;
            $oldType = $transaction->type;
            $oldProductId = $transaction->product_id;
            
            // Revert stok produk lama
            $oldProduct = Product::find($oldProductId);
            if ($oldType === 'in') {
                $oldProduct->stock -= $oldQuantity;
            } else {
                $oldProduct->stock += $oldQuantity;
            }
            $oldProduct->save();
            
            // Update transaksi
            $transaction->update([
                'product_id' => $validated['product_id'],
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'],
                'transaction_date' => $validated['transaction_date'],
            ]);
            
            // Update stok produk baru
            $newProduct = Product::find($validated['product_id']);
            
            if ($validated['type'] === 'in') {
                $newProduct->stock += $validated['quantity'];
            } else {
                // Validasi stok cukup untuk transaksi keluar
                if ($newProduct->stock < $validated['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk transaksi keluar');
                }
                $newProduct->stock -= $validated['quantity'];
            }
            
            $newProduct->save();
        });

        return redirect()->route('inventory.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Show confirmation for deleting the specified resource.
     */
    public function confirmDelete($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        return view('inventory.confirm-delete', compact('transaction'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        
        // Gunakan transaction database untuk memastikan konsistensi data
        DB::transaction(function () use ($transaction) {
            // Revert stok produk sebelum menghapus transaksi
            $product = Product::find($transaction->product_id);
            
            if ($transaction->type === 'in') {
                $product->stock -= $transaction->quantity;
            } else {
                $product->stock += $transaction->quantity;
            }
            
            $product->save();
            
            // Hapus transaksi
            $transaction->delete();
        });

        return redirect()->route('inventory.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}