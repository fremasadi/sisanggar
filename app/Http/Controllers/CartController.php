<?php

namespace App\Http\Controllers;

use App\Models\Kostum;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display cart
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Calculate subtotal (harga per hari)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['harga_sewa'] * $item['quantity'];
        }
        
        // Get previous calculation if exists
        $tanggal_pengambilan = session()->get('tanggal_pengambilan');
        $tanggal_pengembalian = session()->get('tanggal_pengembalian');
        $durasi = session()->get('durasi');
        $total_biaya = session()->get('total_biaya');
        
        return view('front.cart.index', compact(
            'cart', 
            'subtotal',
            'tanggal_pengambilan',
            'tanggal_pengembalian',
            'durasi',
            'total_biaya'
        ));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'kostum_id' => 'required|exists:kostums,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $kostum = Kostum::findOrFail($request->kostum_id);
        
        // Check stock
        // Error stok
        if ($request->quantity > $kostum->stok) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $kostum->stok
            ], 400);
        }


        $cart = session()->get('cart', []);
        
        // Generate unique key: kostum_id + ukuran
        $cartKey = $kostum->id . '_' . $kostum->ukuran;

        // Check if item already in cart
        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $request->quantity;
            
            if ($newQuantity > $kostum->stok) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jumlah melebihi stok. Stok tersedia: ' . $kostum->stok
                    ], 400);
                }

            
            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            $cart[$cartKey] = [
                'kostum_id' => $kostum->id,
                'nama_kostum' => $kostum->nama_kostum,
                'ukuran' => $kostum->ukuran,
                'harga_sewa' => $kostum->harga_sewa,
                'quantity' => $request->quantity,
                'stok' => $kostum->stok,
            ];
        }

        session()->put('cart', $cart);
        
        // Clear previous calculation
        $this->clearCalculation();

        return response()->json([
    'status' => 'success',
    'message' => 'Kostum berhasil ditambahkan ke keranjang!'
]);


    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Item tidak ditemukan di keranjang');
        }

        // Check stock
        if ($request->quantity > $cart[$id]['stok']) {
            return back()->with('error', 'Jumlah melebihi stok. Stok tersedia: ' . $cart[$id]['stok']);
        }

        $cart[$id]['quantity'] = $request->quantity;
        session()->put('cart', $cart);
        
        // Clear previous calculation
        $this->clearCalculation();

        return back()->with('success', 'Jumlah berhasil diupdate');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            
            // Clear calculation if cart is empty
            if (empty($cart)) {
                $this->clearCalculation();
            }
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        $this->clearCalculation();
        
        return back()->with('success', 'Keranjang berhasil dikosongkan');
    }

    /**
     * Calculate total with rental period
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'tanggal_pengambilan' => 'required|date|after_or_equal:today',
            'tanggal_pengembalian' => 'required|date|after:tanggal_pengambilan',
        ], [
            'tanggal_pengambilan.after_or_equal' => 'Tanggal pengambilan tidak boleh kurang dari hari ini',
            'tanggal_pengembalian.after' => 'Tanggal pengembalian harus setelah tanggal pengambilan',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Calculate duration in days
        $tanggalAmbil = new \Carbon\Carbon($request->tanggal_pengambilan);
        $tanggalKembali = new \Carbon\Carbon($request->tanggal_pengembalian);
        $durasi = $tanggalAmbil->diffInDays($tanggalKembali);

        if ($durasi < 1) {
            return back()->with('error', 'Durasi sewa minimal 1 hari');
        }

        // Calculate subtotal (per day)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['harga_sewa'] * $item['quantity'];
        }

        // Calculate total
        $total_biaya = $subtotal * $durasi;

        // Save to session
        session()->put('tanggal_pengambilan', $request->tanggal_pengambilan);
        session()->put('tanggal_pengembalian', $request->tanggal_pengembalian);
        session()->put('durasi', $durasi);
        session()->put('total_biaya', $total_biaya);

        return redirect()->route('cart.index')->with('success', 'Total biaya berhasil dihitung');
    }

    /**
     * Clear calculation from session
     */
    private function clearCalculation()
    {
        session()->forget([
            'tanggal_pengambilan',
            'tanggal_pengembalian',
            'durasi',
            'total_biaya'
        ]);
    }
}