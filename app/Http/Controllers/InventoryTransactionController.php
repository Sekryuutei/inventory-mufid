<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
    public function create(Request $request)
    {
        $products = Product::orderBy('name')->get();
        // Ambil product_id dari query string URL
        $selectedProductId = $request->query('product_id');

        return view('inventory.create', compact('products', 'selectedProductId'));
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

        DB::transaction(function () use ($validated) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            
            if ($validated['type'] === 'in') {
                $product->stock += $validated['quantity'];
            } else {
                // Validasi stok cukup untuk transaksi keluar
                if ($product->stock < $validated['quantity']) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Stok produk tidak mencukupi. Stok saat ini: ' . $product->stock,
                    ]);
                }
                $product->stock -= $validated['quantity'];
            }
            
            $product->save();

            // Buat transaksi inventory
            InventoryTransaction::create($validated);
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
        // Validasi data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

        $transaction = InventoryTransaction::findOrFail($id);

        DB::transaction(function () use ($validated, $transaction) {
            $oldQuantity = $transaction->quantity;
            $oldType = $transaction->type;
            $oldProductId = $transaction->product_id;
            $newProductId = $validated['product_id'];

            // Kunci baris produk yang terlibat untuk mencegah race condition.
            // Urutkan ID untuk mencegah deadlock.
            $productIds = array_unique([$oldProductId, $newProductId]);
            sort($productIds);
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $oldProduct = $products[$oldProductId];
            
            // 1. Kembalikan stok dari transaksi lama
            if ($oldType === 'in') {
                $oldProduct->stock -= $oldQuantity;
            } else {
                $oldProduct->stock += $oldQuantity;
            }

            // Jika produknya diubah, simpan perubahan stok produk lama terlebih dahulu.
            if ($oldProductId != $newProductId) {
                $oldProduct->save();
            }

            $newProduct = $products[$newProductId];

            // 2. Terapkan perubahan stok untuk transaksi baru
            if ($validated['type'] === 'in') {
                $newProduct->stock += $validated['quantity'];
            } else {
                if ($newProduct->stock < $validated['quantity']) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Stok produk tidak mencukupi. Stok saat ini: ' . $newProduct->stock,
                    ]);
                }
                $newProduct->stock -= $validated['quantity'];
            }
            
            $newProduct->save();

            // 3. Perbarui detail transaksi
            $transaction->update($validated);
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
        
        try {
            DB::transaction(function () use ($transaction) {
                // Kunci produk untuk update dan revert stok
                $product = Product::lockForUpdate()->findOrFail($transaction->product_id);
                
                if ($transaction->type === 'in') {
                    // Pastikan stok tidak menjadi negatif setelah revert
                    if ($product->stock < $transaction->quantity) {
                        throw new \Exception('Gagal menghapus transaksi karena akan menghasilkan stok negatif.');
                    }
                    $product->stock -= $transaction->quantity;
                } else {
                    $product->stock += $transaction->quantity;
                }
                
                $product->save();
                
                // Hapus transaksi
                $transaction->delete();
            });
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')
                ->with('error', $e->getMessage());
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}