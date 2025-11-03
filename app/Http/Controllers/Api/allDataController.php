<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Users;
use App\Models\Products;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class allDataController extends Controller
{
    //USER SESSION
    function userSession($sessionId){
       $user = Users::where('id_user', $sessionId)->first();

       return $user;
    }

    //PRODUCT
    function allProducts(){
        $products = Products::join('product_category','product_category.id_kategori','=','products.id_kategori')->get();

        if(count($products) > 0){
            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    function allPopularProduct(){
        $products = Products::join('transactions','transactions.id_produk','=','products.id_produk')
        ->where('jumlah','>','200')
        ->get();

        if(count($products) > 0){
            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    function details($product_code, $product_slug){
        $prod = Products::join('product_category','product_category.id_kategori','=','products.id_kategori')
        ->where('kode_produk', $product_code)
        ->where('slug_produk', $product_slug)
        ->get();

        if(count($prod) > 0){
            return response()->json([
                'success' => true,
                'data' => $prod
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ]);
        }
    }

    function relatedProducts($product_code, $product_slug){
        // First get the current product to find its category
        $currentProduct = Products::where('kode_produk', $product_code)
        ->where('slug_produk', $product_slug)
        ->first();

        if(!$currentProduct){
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        // Get related products from the same category, excluding current product
        $relatedProducts = Products::join('product_category','product_category.id_kategori','=','products.id_kategori')
        ->where('products.id_kategori', $currentProduct->id_kategori)
        ->where('products.id_produk', '!=', $currentProduct->id_produk)
        ->limit(4)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $relatedProducts
        ], 200);
    }

    function productImages($slug){
        $prod = Products::where('slug_produk', $slug)->first();

        if($prod){
            $images = [];
            if($prod->gambar_produk){
                $images[] = '/images/products/' . $prod->gambar_produk;
            }
            // Add sample images
            $sampleImages = ['seni_1.png', 'seni_2.png', 'seni_3.png'];
            foreach($sampleImages as $sample){
                $images[] = '/images/products/sample/' . $sample;
            }

            return response()->json([
                'success' => true,
                'data' => $images
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ]);
        }
    }

    function addProduct(Request $request){
        $validatedData = $request->validate([
            'kode_produk' => 'nullable',
            'id_kategori' => 'required',
            'nama_produk' => 'required',
            'stok_produk' => 'required',
            'slug_produk' => 'nullable',
            'detail_produk' => 'required',
            'harga_produk' => 'required',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $validatedData['slug_produk'] = Str::slug($validatedData['nama_produk']);
        $validatedData['kode_produk'] = "ART-" . rand(0,1000) . date('Ymd');

        $imageName = "";

        if($request->hasFile('gambar_produk')){

            $img = $request->file('gambar_produk');
            $imageName = "ART-" . date('Ymd') . rand(0, 1000) . '.' . $img->getClientOriginalExtension();
            $destinationPath = public_path('images/products');

            if(!File::exists($destinationPath)){
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $img->move($destinationPath, $imageName);
        }

        $validatedData['gambar_produk'] = $imageName;

        $prod = Products::create($validatedData);

        if($prod){
            return response()->json([
                'status' => true,
            ], 200);
        }else{
            return response()->json([
                'status' => false
            ], 200);
        }
    }

    function editProduct(Request $request, $id){
        $product = Products::find($id);

        if(!$product){
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validatedData = $request->validate([
            'id_kategori' => 'required',
            'nama_produk' => 'required',
            'stok_produk' => 'required',
            'detail_produk' => 'required',
            'harga_produk' => 'required',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $validatedData['slug_produk'] = Str::slug($validatedData['nama_produk']);

        if($request->hasFile('gambar_produk')){
            // Delete old image if exists
            if($product->gambar_produk && File::exists(public_path('images/products/' . $product->gambar_produk))){
                File::delete(public_path('images/products/' . $product->gambar_produk));
            }

            $img = $request->file('gambar_produk');
            $imageName = "ART-" . date('Ymd') . rand(0, 1000) . '.' . $img->getClientOriginalExtension();
            $destinationPath = public_path('images/products');

            if(!File::exists($destinationPath)){
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $img->move($destinationPath, $imageName);
            $validatedData['gambar_produk'] = $imageName;
        }

        $product->update($validatedData);

        return response()->json([
            'status' => true,
        ], 200);
    }

    function deleteProduct(Request $request, $id){
        $product = Products::find($id);

        if(!$product){
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Delete image if exists
        if($product->gambar_produk && File::exists(public_path('images/products/' . $product->gambar_produk))){
            File::delete(public_path('images/products/' . $product->gambar_produk));
        }

        $product->delete();

        return response()->json([
            'status' => true,
        ], 200);
    }

    //PRODUCT CATEGORY
    function allProductCategory(){
        $category = ProductCategory::all();

        if(count($category) > 0){
            return response()->json([
                'success' => true,
                'data' => $category
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    function productsByCategory($id_kategori){
        $products = Products::join('product_category','product_category.id_kategori','=','products.id_kategori')
            ->where('products.id_kategori', $id_kategori)
            ->get();

        if(count($products) > 0){
            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    function addProductCategory(Request $request){
        $validatedData = $request->validate([
            'nama_kategori' => 'required'
        ]);

        $pc = ProductCategory::create($validatedData);

        if($pc){
            return response()->json([
                'success' => true
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    function updateProfile(Request $request){
        $validatedData = $request->validate([
            'nama_tampilan' => 'required',
            'email' => 'required|email'
        ]);

        $user = Users::where('id_user', session('userId'))->first();
        $user->nama_tampilan = $validatedData['nama_tampilan'];
        $user->email = $validatedData['email'];
        $saved = $user->save();

        if($saved){
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil.'
            ], 200);
        }
    }

    function updatePassword(Request $request){
        if(!session('userLogin') || session('userRole') != 'customer'){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $validatedData = $request->validate([
                'old_pass' => 'required',
                'new_pass' => 'required|min:6',
                'confirm_pass' => 'required|same:new_pass'
            ]);

            $user = Users::where('id_user', session('userId'))->first();

            if(!$user){
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            if(!Hash::check($validatedData['old_pass'], $user->password)){
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah!'
                ], 200);
            }

            $user->password = Hash::make($validatedData['new_pass']);
            $saved = $user->save();

            if($saved){
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil diperbarui!'
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui password.'
                ], 200);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();

            // Custom error messages
            if (isset($errors['new_pass'])) {
                foreach ($errors['new_pass'] as $error) {
                    if (str_contains($error, 'minimum') || str_contains($error, 'at least')) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Password minimal 6 karakter.'
                        ], 422);
                    }
                }
            }

            if (isset($errors['confirm_pass'])) {
                foreach ($errors['confirm_pass'] as $error) {
                    if (str_contains($error, 'same')) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Konfirmasi password tidak cocok.'
                        ], 422);
                    }
                }
            }

            if (isset($errors['old_pass'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama harus diisi.'
                ], 422);
            }

            // Fallback for other errors
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                $errorMessages = array_merge($errorMessages, $messages);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $errorMessages)
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    function getUserProfile(){
        if(!session('userLogin') || session('userRole') != 'customer'){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Users::find(session('userId'));

        if($user){
            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    function checkLogin(){
        return response()->json([
            'logged_in' => session('userLogin') ? true : false
        ]);
    }

    public function addToCart(Request $request)
    {
        // 1. Cek apakah pengguna sudah login
        if (!session('userLogin')) {
            // Kirim respons JSON jika belum login
            return response()->json([
                'success' => false,
                'message' => 'Not logged in'
            ], 401); // 401 artinya Unauthorized
        }

        try {
            // 2. Lakukan validasi data yang masuk
            $validatedData = $request->validate([
                'id_produk' => 'required|integer|exists:products,id_produk', // Pastikan produk ada
                'jumlah' => 'required|integer|min:1'
            ]);

            // 3. Cek apakah produk sudah ada di keranjang
            $existingCart = Cart::where('id_produk', $validatedData['id_produk'])
                                ->where('id_user', session('userId'))
                                ->first();

            if ($existingCart) {
                // Jika sudah ada, tambahkan jumlah
                $existingCart->jumlah += $validatedData['jumlah'];
                $existingCart->save();
            } else {
                // Jika belum ada, buat entry baru
                $cartData = [
                    'id_user' => session('userId'),
                    'id_produk' => $validatedData['id_produk'],
                    'jumlah' => $validatedData['jumlah'],
                ];
                Cart::create($cartData);
            }

            // 4. Kirim respons JSON bahwa operasi berhasil
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!'
            ]);

        } catch (ValidationException $e) {
            // 5. Jika validasi gagal, tangkap errornya dan kirim sebagai respons JSON
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors() // Kirim detail errornya
            ], 422); // 422 artinya Unprocessable Entity
        }
    }

    function getCart(){
        if(!session('userLogin')){
            return response()->json([
                'success' => false,
                'message' => 'Not logged in'
            ]);
        }

        $cart = Cart::join('products','products.id_produk','=','cart.id_produk')
        ->where('id_user', session('userId'))
        ->get();

        if($cart){
            return response()->json([
                'success' => true,
                'data' => $cart
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    function updateCart(Request $request){
        if(!session('userLogin')){
            return response()->json([
                'success' => false,
                'message' => 'Not logged in'
            ]);
        }

        $validatedData = $request->validate([
            'id_produk' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('id_produk', $validatedData['id_produk'])
                        ->where('id_user', session('userId'))
                        ->first();

        if($cartItem){
            $cartItem->jumlah = $validatedData['jumlah'];
            $cartItem->save();
            return response()->json([
                'success' => true
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Product not in cart'
            ]);
        }
    }

    function removeFromCart(Request $request){
        if(!session('userLogin')){
            return response()->json([
                'success' => false,
                'message' => 'Not logged in'
            ]);
        }

        $validatedData = $request->validate([
            'id_produk' => 'required'
        ]);

        $deleted = Cart::where('id_produk', $validatedData['id_produk'])
                       ->where('id_user', session('userId'))
                       ->delete();

        if($deleted > 0){
            return response()->json([
                'success' => true
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);
        }
    }

    function addressByUser($id_user){
        $custAddr = CustomerAddress::where('id_user', $id_user)->get();

        if($custAddr){
            return response()->json([
                'success' => true,
                'data' => $custAddr
            ], 200);
        }else{
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    function allTransactions(Request $request){
        $query = Transactions::join('users', 'transactions.id_user', '=', 'users.id_user')
            ->select(
                'transactions.kode_transaksi',
                'transactions.id_user',
                'transactions.id_alamat',
                DB::raw('SUM(transactions.jumlah) as total_jumlah'),
                DB::raw('SUM(transactions.total) as grand_total'),
                'transactions.tanggal_dan_waktu_pembelian',
                'transactions.metode_pembayaran',
                'transactions.jasa_pengiriman',
                DB::raw('MAX(transactions.status_pembayaran) as status_pembayaran'),
                DB::raw('MAX(transactions.status_pengiriman) as status_pengiriman'),
                DB::raw('MAX(transactions.bukti_pembayaran) as bukti_pembayaran'),
                'users.nama_tampilan as customer_name',
                'users.email as customer_email'
            )
            ->groupBy('transactions.kode_transaksi', 'transactions.id_user', 'transactions.id_alamat', 'transactions.tanggal_dan_waktu_pembelian', 'transactions.metode_pembayaran', 'transactions.jasa_pengiriman', 'users.nama_tampilan', 'users.email');

        // Apply date filters
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('transactions.tanggal_dan_waktu_pembelian', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('transactions.tanggal_dan_waktu_pembelian', '<=', $request->end_date);
        }

        // Apply payment status filter
        if ($request->has('payment_status') && $request->payment_status && $request->payment_status !== 'Semua Status Pembayaran') {
            $paymentStatusMapping = [
                'unpaid' => 'Belum Bayar',
                'pending' => 'Menunggu Verifikasi',
                'paid' => 'Lunas',
                'rejected' => 'Ditolak'
            ];

            if (isset($paymentStatusMapping[$request->payment_status])) {
                $query->having('status_pembayaran', '=', $paymentStatusMapping[$request->payment_status]);
            }
        }

        // Apply shipping status filter
        if ($request->has('shipping_status') && $request->shipping_status && $request->shipping_status !== 'Semua Status Pengiriman') {
            $shippingStatusMapping = [
                'belum_dikirim' => 'Belum Dikirim',
                'sedang_dikirim' => 'Sedang Dikirim',
                'diterima' => 'Diterima'
            ];

            if (isset($shippingStatusMapping[$request->shipping_status])) {
                $query->having('status_pengiriman', '=', $shippingStatusMapping[$request->shipping_status]);
            }
        }

        $trans = $query->get();

        if($trans){
            return response()->json([
                'success' => true,
                'data' => $trans
            ]);
        }else{
            return response()->json([
                'success' => false
            ]);
        }
    }

    function allTransactionsByTransCode($trcode){
        $tr = Transactions::join('products','products.id_produk','=','transactions.id_produk')
        ->join('users','users.id_user','=','transactions.id_user')
        ->join('customer_address','customer_address.id_alamat','=','transactions.id_alamat')
        ->where('kode_transaksi', $trcode)->get();

        if($tr){
            return response()->json([
                'success' => true,
                'data' => $tr
            ]);
        }else{
            return response()->json([
                'success' => false
            ]);
        }
    }

    function createTransactions(Request $request){
        $validatedData = $request->validate([
            'id_user' => 'required|integer|exists:users,id_user',
            'id_alamat' => 'required|integer|exists:customer_address,id_alamat',
            'metode_pembayaran' => 'required|string',
            'metode_pengiriman' => 'required|string',
            'total' => 'required|integer|min:0',
        ]);

        try {
            // Get cart items for the user with product details
            $cartItems = Cart::join('products','products.id_produk','=','cart.id_produk')
                ->where('id_user', $validatedData['id_user'])
                ->get();

            if($cartItems->isEmpty()){
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            $tgl = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $kode_transaksi = "TR-" . rand(100, 999) . time();

            $transactions = [];
            foreach($cartItems as $item){
                $trans = new Transactions;
                $trans->kode_transaksi = $kode_transaksi;
                $trans->id_produk = $item->id_produk;
                $trans->id_user = $validatedData['id_user'];
                $trans->id_alamat = $validatedData['id_alamat'];
                $trans->jumlah = $item->jumlah;
                $trans->total = $item->jumlah * $item->harga_produk; // Calculate per item
                $trans->tanggal_dan_waktu_pembelian = $tgl;
                $trans->metode_pembayaran = $validatedData['metode_pembayaran'];
                $trans->jasa_pengiriman = $validatedData['metode_pengiriman'];
                $trans->status_pembayaran = 'Belum Bayar';
                $trans->status_pengiriman = 'Belum Dikirim';
                $trans->save();
                $transactions[] = $trans;
            }

            if(!empty($transactions)){
                // Delete cart items
                Cart::where('id_user', $validatedData['id_user'])->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil dibuat'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat transaksi'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    function addAddress(Request $request){
        $validatedData = $request->validate([
            'id_user' => 'required|integer|exists:users,id_user',
            'alamat' => 'required|string|max:255',
            'nama_provinsi' => 'required|string|max:100',
            'nama_kota' => 'required|string|max:100',
            'nama_kecamatan' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10'
        ]);

        try {
            $addr = CustomerAddress::create($validatedData);

            if($addr){
                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil ditambahkan!'
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan alamat.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    function setPrimaryAddress(Request $request){
        $validatedData = $request->validate([
            'id_alamat' => 'required|integer|exists:customer_address,id_alamat',
            'id_user' => 'required|integer|exists:users,id_user'
        ]);

        try {
            // First, set all addresses for this user to non-primary
            CustomerAddress::where('id_user', $validatedData['id_user'])
                          ->update(['is_primary' => false]);

            // Then set the selected address as primary
            $address = CustomerAddress::where('id_alamat', $validatedData['id_alamat'])
                                     ->where('id_user', $validatedData['id_user'])
                                     ->first();

            if($address){
                $address->is_primary = true;
                $address->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Alamat utama berhasil diubah!'
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Alamat tidak ditemukan.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    function getProvinces(){
        $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

        if($response->successful()){
            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        }else{
            return response()->json([
                'success' => false
            ]);
        }
    }

    function getRegencies($provinceId){
        $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provinceId}.json");

        if($response->successful()){
            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        }else{
            return response()->json([
                'success' => false
            ]);
        }
    }

    function getDistricts($regencyId){
        $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$regencyId}.json");

        if($response->successful()){
            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        }else{
            return response()->json([
                'success' => false
            ]);
        }
    }

    function testShippingSystem(){
        return response()->json([
            'success' => true,
            'message' => 'Shipping system is using enhanced city-based calculation',
            'api_provider' => env('SHIPPING_API_PROVIDER', 'fallback'),
            'base_cost' => env('SHIPPING_BASE_COST', 50000),
            'features' => [
                'city_specific_pricing' => true,
                'multiple_couriers' => true,
                'dynamic_calculation' => true,
                'fallback_system' => true
            ],
            'supported_cities' => [
                'Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur',
                'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Makassar', 'Denpasar'
            ]
        ]);
    }

    function calculateShippingCost(Request $request){
        $validatedData = $request->validate([
            'id_alamat' => 'required|integer|exists:customer_address,id_alamat',
            'weight' => 'nullable|integer|min:1'
        ]);

        try {
            $address = CustomerAddress::find($validatedData['id_alamat']);

            if(!$address){
                return response()->json([
                    'success' => false,
                    'message' => 'Alamat tidak ditemukan.'
                ], 404);
            }

            // Since RajaOngkir API is deprecated, use enhanced city-based calculation
            Log::info('Using enhanced city-based shipping calculation', [
                'address_id' => $validatedData['id_alamat'],
                'city' => $address->nama_kota,
                'province' => $address->nama_provinsi
            ]);

            return $this->enhancedCityBasedShippingCalculation($address, $validatedData['weight'] ?? 1000);

        } catch (\Exception $e) {
            Log::error('Shipping calculation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    private function findCityIdByName($cityName){
        try {
            // Try to get city data from RajaOngkir API
            $response = Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->get(env('RAJAONGKIR_BASE_URL') . '/city');

            if($response->successful()){
                $data = $response->json();
                if(isset($data['rajaongkir']['results'])){
                    // Search for city by name
                    foreach($data['rajaongkir']['results'] as $city){
                        if(strtolower($city['city_name']) === strtolower($cityName)){
                            return (int)$city['city_id'];
                        }
                        // Also check if city name contains the search term
                        if(str_contains(strtolower($city['city_name']), strtolower($cityName))){
                            return (int)$city['city_id'];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error but continue
            Log::error('RajaOngkir city API error: ' . $e->getMessage());
        }

        // Fallback to static mapping for common cities
        $cityMappings = [
            'Jakarta Pusat' => 152,
            'Jakarta Utara' => 153,
            'Jakarta Barat' => 154,
            'Jakarta Selatan' => 155,
            'Jakarta Timur' => 156,
            'Bandung' => 23,
            'Surabaya' => 444,
            'Semarang' => 369,
            'Yogyakarta' => 419,
            'Malang' => 273,
            'Medan' => 288,
            'Palembang' => 348,
            'Makassar' => 267,
            'Denpasar' => 128,
            'Balikpapan' => 22,
            'Samarinda' => 405,
            'Pekanbaru' => 343,
            'Padang' => 327,
            'Bandar Lampung' => 24,
            'Serang' => 415,
            'Tangerang' => 456,
            'Bekasi' => 32,
            'Depok' => 135,
            'Bogor' => 42,
            'Cirebon' => 80,
            'Tasikmalaya' => 457,
            'Cimahi' => 78,
            'Sukabumi' => 442,
            'Purwokerto' => 365,
            'Klaten' => 238,
            'Madiun' => 268,
            'Kediri' => 229,
            'Blitar' => 41,
            'Tulungagung' => 473,
            'Tuban' => 472,
            'Lamongan' => 248,
            'Mojokerto' => 295,
            'Jombang' => 221,
            'Nganjuk' => 308,
            'Magetan' => 269,
            'Ngawi' => 309,
            'Bojonegoro' => 43,
            'Purwodadi' => 366,
            'Kudus' => 245,
            'Pati' => 340,
            'Rembang' => 377,
            'Blora' => 40,
            'Tegal' => 458,
            'Pemalang' => 344,
            'Purbalingga' => 364,
            'Banjarnegara' => 25,
            'Wonosobo' => 497,
            'Magelang' => 270,
            'Temanggung' => 459,
            'Kendal' => 231,
            'Batang' => 29,
            'Pekalongan' => 342,
            'Salatiga' => 396,
            'Boyolali' => 47,
            'Sukoharjo' => 441,
            'Wonogiri' => 496,
            'Karanganyar' => 226,
            'Sragen' => 430,
            'Grobogan' => 189,
            'Demak' => 134,
            'Jepara' => 218,
            'Juwana' => 224,
            'Kudus' => 245,
            'Pati' => 340,
            'Rembang' => 377,
            'Blora' => 40,
            'Tegal' => 458,
            'Pemalang' => 344,
            'Purbalingga' => 364,
            'Banjarnegara' => 25,
            'Wonosobo' => 497,
            'Magelang' => 270,
            'Temanggung' => 459,
            'Kendal' => 231,
            'Batang' => 29,
            'Pekalongan' => 342,
            'Salatiga' => 396,
            'Boyolali' => 47,
            'Sukoharjo' => 441,
            'Wonogiri' => 496,
            'Karanganyar' => 226,
            'Sragen' => 430,
            'Grobogan' => 189,
            'Demak' => 134,
            'Jepara' => 218,
            'Juwana' => 224,
        ];

        return $cityMappings[$cityName] ?? null;
    }

    private function enhancedCityBasedShippingCalculation($address, $weight = 1000){
        // Enhanced calculation with different prices for different cities
        $baseCost = 50000;
        $cityName = strtolower($address->nama_kota);
        $provinceName = strtolower($address->nama_provinsi);

        // City-specific pricing (more granular than province-based)
        $cityMultipliers = [
            // Jakarta area - lowest cost
            'jakarta pusat' => 1.0,
            'jakarta utara' => 1.0,
            'jakarta barat' => 1.0,
            'jakarta selatan' => 1.0,
            'jakarta timur' => 1.0,
            'tangerang' => 1.1,
            'bekasi' => 1.1,
            'depok' => 1.1,
            'bogor' => 1.2,

            // Bandung area
            'bandung' => 1.3,
            'cimahi' => 1.3,
            'tasikmalaya' => 1.4,

            // Surabaya area
            'surabaya' => 1.4,
            'sidoarjo' => 1.4,
            'gresik' => 1.4,
            'lamongan' => 1.5,

            // Semarang area
            'semarang' => 1.3,
            'salatiga' => 1.3,
            'ungaran' => 1.3,

            // Yogyakarta area
            'yogyakarta' => 1.3,
            'sleman' => 1.3,
            'bantul' => 1.3,

            // Major cities
            'medan' => 2.0,
            'palembang' => 1.8,
            'makassar' => 2.2,
            'denpasar' => 1.6,
            'balikpapan' => 2.0,
            'samarinda' => 2.1,
            'pekanbaru' => 1.9,
            'padang' => 1.8,
            'bandar lampung' => 1.7,
        ];

        // Check if city has specific pricing
        $multiplier = $cityMultipliers[$cityName] ?? null;

        if(!$multiplier){
            // Province-based fallback
            if(str_contains($provinceName, 'jakarta') || str_contains($provinceName, 'dki jakarta')){
                $multiplier = 1.0;
            } elseif(str_contains($provinceName, 'jawa barat') || str_contains($provinceName, 'banten') || str_contains($provinceName, 'jawa tengah') || str_contains($provinceName, 'yogyakarta') || str_contains($provinceName, 'di yogyakarta')){
                $multiplier = 1.3;
            } elseif(str_contains($provinceName, 'jawa timur') || str_contains($provinceName, 'jawa timur')){
                $multiplier = 1.4;
            } elseif(str_contains($provinceName, 'bali') || str_contains($provinceName, 'nusa tenggara')){
                $multiplier = 1.6;
            } elseif(str_contains($provinceName, 'sumatera') || str_contains($provinceName, 'aceh') || str_contains($provinceName, 'riau') || str_contains($provinceName, 'jambi') || str_contains($provinceName, 'bengkulu') || str_contains($provinceName, 'lampung')){
                $multiplier = 1.8;
            } elseif(str_contains($provinceName, 'kalimantan') || str_contains($provinceName, 'sulawesi') || str_contains($provinceName, 'maluku') || str_contains($provinceName, 'papua') || str_contains($provinceName, 'nusa tenggara timur')){
                $multiplier = 2.0;
            } else {
                $multiplier = 1.5; // Default
            }
        }

        $shippingCost = $baseCost * $multiplier;

        // Create multiple shipping options with different services
        $shippingOptions = [
            [
                'courier' => 'JNE',
                'service' => 'REG',
                'description' => 'Layanan Reguler',
                'cost' => (int)$shippingCost,
                'etd' => '2-3',
                'formatted_cost' => 'Rp ' . number_format($shippingCost, 0, ',', '.')
            ],
            [
                'courier' => 'JNE',
                'service' => 'YES',
                'description' => 'Yakin Esok Sampai',
                'cost' => (int)($shippingCost * 1.3),
                'etd' => '1-2',
                'formatted_cost' => 'Rp ' . number_format($shippingCost * 1.3, 0, ',', '.')
            ],
            [
                'courier' => 'TIKI',
                'service' => 'REG',
                'description' => 'Layanan Reguler',
                'cost' => (int)($shippingCost * 1.1),
                'etd' => '2-4',
                'formatted_cost' => 'Rp ' . number_format($shippingCost * 1.1, 0, ',', '.')
            ],
            [
                'courier' => 'POS',
                'service' => 'Paket Kilat Khusus',
                'description' => 'Paket Kilat Khusus',
                'cost' => (int)($shippingCost * 1.2),
                'etd' => '2-5',
                'formatted_cost' => 'Rp ' . number_format($shippingCost * 1.2, 0, ',', '.')
            ]
        ];

        // Sort by cost
        usort($shippingOptions, function($a, $b) {
            return $a['cost'] <=> $b['cost'];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'shipping_options' => $shippingOptions,
                'cheapest' => $shippingOptions[0],
                'destination' => $address->nama_kota . ', ' . $address->nama_provinsi,
                'enhanced_calculation' => true,
                'multiplier' => $multiplier
            ]
        ], 200);
    }

    function searchProductByName($name){
       $prd = Products::join('product_category','product_category.id_kategori','=','products.id_kategori')
       ->where('nama_produk', 'LIKE', '%' . $name . '%')
       ->get();

       if($prd){
        return response()->json([
            'success' => true,
            'data' => $prd
        ]);
       }else{
        return response()->json([
            'success' => false
        ]);
       }
    }

    function allCustomers(){
        $customers = Users::where('role', 'customer')
            ->select(
                'users.id_user',
                'users.nama_tampilan',
                'users.email',
                'users.created_at'
            )
            ->orderBy('users.created_at', 'desc')
            ->get();

        if(count($customers) > 0){
            return response()->json([
                'success' => true,
                'data' => $customers
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'No customers found'
            ], 200);
        }
    }

    function dashboardStats(){
        // Current month
        $currentMonth = now();
        $previousMonth = now()->subMonth();

        // Total Pendapatan - Current month
        $totalRevenue = Transactions::whereYear('tanggal_dan_waktu_pembelian', $currentMonth->year)
            ->whereMonth('tanggal_dan_waktu_pembelian', $currentMonth->month)
            ->sum('total');

        // Total Pendapatan - Previous month
        $previousRevenue = Transactions::whereYear('tanggal_dan_waktu_pembelian', $previousMonth->year)
            ->whereMonth('tanggal_dan_waktu_pembelian', $previousMonth->month)
            ->sum('total');

        // Calculate revenue percentage change
        $revenueChangePercent = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        // Produk Terjual - Current month
        $productsSold = Transactions::whereYear('tanggal_dan_waktu_pembelian', $currentMonth->year)
            ->whereMonth('tanggal_dan_waktu_pembelian', $currentMonth->month)
            ->sum('jumlah');

        // Produk Terjual - Previous month
        $previousProductsSold = Transactions::whereYear('tanggal_dan_waktu_pembelian', $previousMonth->year)
            ->whereMonth('tanggal_dan_waktu_pembelian', $previousMonth->month)
            ->sum('jumlah');

        // Calculate products sold percentage change
        $productsSoldChangePercent = $previousProductsSold > 0 ? (($productsSold - $previousProductsSold) / $previousProductsSold) * 100 : 0;

        // Pelanggan Baru - Current month
        $newCustomers = Users::whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();

        // Pelanggan Baru - Previous month
        $previousNewCustomers = Users::whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();

        // Calculate new customers percentage change
        $newCustomersChangePercent = $previousNewCustomers > 0 ? (($newCustomers - $previousNewCustomers) / $previousNewCustomers) * 100 : 0;

        // Transaksi Tertunda (assuming transactions without payment method are pending)
        $pendingTransactions = Transactions::whereNull('metode_pembayaran')->count();

        // Previous pending transactions (this month vs last month)
        $previousPendingTransactions = Transactions::whereNull('metode_pembayaran')
            ->whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();

        // Calculate pending transactions change (absolute change, not percentage)
        $pendingTransactionsChange = $pendingTransactions - $previousPendingTransactions;

        // Recent Transactions
        $recentTransactions = Transactions::with('user', 'product')
            ->orderBy('tanggal_dan_waktu_pembelian', 'desc')
            ->take(4)
            ->get()
            ->map(function($transaction) {
                return [
                    'customer_name' => $transaction->user->nama_tampilan ?? 'Unknown',
                    'product_name' => $transaction->product->nama_produk ?? 'Unknown',
                    'amount' => 'Rp ' . number_format($transaction->total, 0, ',', '.')
                ];
            });

        // Sales data for chart (last 7 months)
        $salesData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M');
            $sales = Transactions::whereYear('tanggal_dan_waktu_pembelian', $month->year)
                ->whereMonth('tanggal_dan_waktu_pembelian', $month->month)
                ->sum('total') / 1000000; // in millions
            $salesData[] = round($sales, 1);
        }

        // If no data, provide sample data for demo
        if (array_sum($salesData) == 0) {
            $salesData = [5.2, 8.1, 6.7, 12.3, 9.8, 15.4, 11.2];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'totalRevenue' => $totalRevenue,
                'productsSold' => $productsSold,
                'newCustomers' => $newCustomers,
                'pendingTransactions' => $pendingTransactions,
                'revenueChangePercent' => round($revenueChangePercent, 1),
                'productsSoldChangePercent' => round($productsSoldChangePercent, 1),
                'newCustomersChangePercent' => round($newCustomersChangePercent, 1),
                'pendingTransactionsChange' => $pendingTransactionsChange,
                'recentTransactions' => $recentTransactions,
                'salesData' => $salesData,
                'salesLabels' => $labels
            ]
        ]);
    }

    function getAdminProfile(){
        $user = Users::find(session('userId'));

        if($user){
            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    function updateAdminProfile(Request $request){
        if(session('userLogin') != true){
            return redirect('admin');
        }

        $validatedData = $request->validate([
            'nama_tampilan' => 'required',
            'email' => 'required|email'
        ]);

        $user = Users::where('id_user', session('userId'))->first();
        $user->nama_tampilan = $validatedData['nama_tampilan'];
        $user->email = $validatedData['email'];
        $saved = $user->save();

        if($saved){
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil.'
            ], 200);
        }
    }

    function updateAdminPassword(Request $request){
        $userId = session('userId');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Session tidak valid. Silakan login kembali.'
            ], 401);
        }

        $validatedData = $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required|min:6',
            'confirm_pass' => 'required|same:new_pass'
        ]);

        $user = Users::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        if(!Hash::check($validatedData['old_pass'], $user->password)){
            return response()->json([
                'success' => false,
                'message' => 'Password lama salah!'
            ], 200);
        }

        $user->password = Hash::make($validatedData['new_pass']);
        $saved = $user->save();

        if($saved){
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui!'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui password.'
            ], 200);
        }
    }

    function getBankAccounts(){
        $accounts = BankAccount::all();

        return response()->json([
            'success' => true,
            'data' => $accounts
        ], 200);
    }

    function getBankAccountsActive(){
        $accounts = BankAccount::where('aktif','ya')->get();

        return response()->json([
            'success' => true,
            'data' => $accounts
        ], 200);
    }

    public function addBankAccount(Request $request)
    {
        // Menggunakan Validator agar bisa custom pesan error
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|numeric|digits_between:8,16', // Validasi yang lebih baik
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() // Ambil pesan error pertama
            ], 422);
        }

        try {
            // Data yang akan disimpan ke database
            $dataToCreate = [
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'tanggal_ditambahkan' => now(), // Tanggal di-set di sini, bukan dari frontend
            ];

            // Membuat record baru
            BankAccount::create($dataToCreate);

            // Respon sukses
            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil ditambahkan!'
            ], 200);

        } catch (Exception $e) {
            // Respon jika ada error server
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    function editBankAccount(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|numeric|digits_between:8,16',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $bank = BankAccount::find($id);

        if($bank){
            $bank->nama_bank = $request->nama_bank;
            $bank->nomor_rekening = $request->nomor_rekening;
            $bank->save();

            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil diupdate!'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Rekening bank tidak ditemukan.'
            ], 404);
        }
    }

    function bankEdit($id){
        $bank = BankAccount::find($id);
        if ($bank) {
            return response()->json([
                'success' => true,
                'data' => $bank
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Rekening bank tidak ditemukan.'
        ], 404);
    }

    function deleteBankAccount($id){
        $account = BankAccount::find($id);

        if(!$account){
            return response()->json([
                'success' => false,
                'message' => 'Rekening bank tidak ditemukan.'
            ], 404);
        }

        $deleted = $account->delete();

        if($deleted){
            return response()->json([
                'success' => true,
                'message' => 'Rekening bank berhasil dihapus!'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus rekening bank.'
            ], 200);
        }
    }

    function bankActive($id_bank)
    {
        $bank = BankAccount::find($id_bank);

        if ($bank) {
            $bank->aktif = $bank->aktif == "ya" ? "tidak" : "ya";
            $bank->save();

            return response()->json([
                'success' => true,
                'message' => $bank->aktif == "ya" ? 'Rekening bank berhasil diaktifkan!' : 'Rekening bank berhasil dinonaktifkan!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Rekening bank tidak ditemukan.'
            ], 404);
        }
    }
    function uploadPaymentProof(Request $request){
        if(!session('userLogin') || session('userRole') != 'customer'){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validatedData = $request->validate([
            'kode_transaksi' => 'required|string',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        try {
            // Check if transaction exists and belongs to user
            $transaction = DB::table('transactions')
                ->where('kode_transaksi', $validatedData['kode_transaksi'])
                ->where('id_user', session('userId'))
                ->first();

            if(!$transaction){
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            // Save the image
            $imageFile = $request->file('bukti_pembayaran');
            $imageName = "PAY-" . $validatedData['kode_transaksi'] . "-" . time() . '.' . $imageFile->getClientOriginalExtension();
            $destinationPath = public_path('images/pembayaran');

            if(!File::exists($destinationPath)){
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $imageFile->move($destinationPath, $imageName);

            // Update transaction status
            DB::table('transactions')
                ->where('kode_transaksi', $validatedData['kode_transaksi'])
                ->where('id_user', session('userId'))
                ->update([
                    'status_pembayaran' => 'Menunggu Verifikasi',
                    'bukti_pembayaran' => $imageName,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload dan akan diverifikasi oleh admin.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    function verifyPayment(Request $request){
        if(!session('userLogin') || session('userRole') != 'admin'){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validatedData = $request->validate([
            'kode_transaksi' => 'required|string',
            'action' => 'required|in:approve,reject'
        ]);

        try {
            if($validatedData['action'] === 'approve'){
                $newStatus = 'Lunas';
                $message = 'Pembayaran berhasil disetujui';
            } else {
                $newStatus = 'Ditolak';
                $message = 'Pembayaran berhasil ditolak';
            }

            DB::table('transactions')
                ->where('kode_transaksi', $validatedData['kode_transaksi'])
                ->update([
                    'status_pembayaran' => $newStatus,
                    'updated_at' => now()
                ]);


            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    function updateShippingStatus(Request $request){
        if(!session('userLogin') || session('userRole') != 'admin'){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validatedData = $request->validate([
            'kode_transaksi' => 'required|string',
            'status_pengiriman' => 'required|in:Belum Dikirim,Sedang Dikirim,Diterima'
        ]);

        try {
            // Check if transaction exists
            $transactionExists = DB::table('transactions')
                ->where('kode_transaksi', $validatedData['kode_transaksi'])
                ->exists();

            if(!$transactionExists){
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            // Update shipping status for all items in the transaction
            DB::table('transactions')
                ->where('kode_transaksi', $validatedData['kode_transaksi'])
                ->update([
                    'status_pengiriman' => $validatedData['status_pengiriman'],
                    'updated_at' => now()
                ]);

            $statusMessages = [
                'Belum Dikirim' => 'Status pengiriman berhasil diubah menjadi Belum Dikirim',
                'Sedang Dikirim' => 'Status pengiriman berhasil diubah menjadi Sedang Dikirim',
                'Diterima' => 'Status pengiriman berhasil diubah menjadi Diterima'
            ];

            return response()->json([
                'success' => true,
                'message' => $statusMessages[$validatedData['status_pengiriman']]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    function getUserData(){
        if(!session('userLogin') || session('userRole') != 'customer'){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Users::find(session('userId'));

        // Get transactions grouped by kode_transaksi
        $transactions = DB::table('transactions')
            ->join('products', 'transactions.id_produk', '=', 'products.id_produk')
            ->where('transactions.id_user', session('userId'))
            ->select(
                'transactions.kode_transaksi',
                'transactions.id_produk',
                'transactions.jumlah',
                'transactions.total',
                'transactions.tanggal_dan_waktu_pembelian',
                'transactions.status_pembayaran',
                'transactions.status_pengiriman',
                'transactions.metode_pembayaran',
                'transactions.jasa_pengiriman',
                'products.nama_produk',
                'products.kode_produk',
                'products.harga_produk'
            )
            ->orderBy('transactions.tanggal_dan_waktu_pembelian', 'desc')
            ->get();

        // Group transactions by kode_transaksi
        $groupedTransactions = [];
        foreach($transactions as $transaction) {
            $kode = $transaction->kode_transaksi;
            if(!isset($groupedTransactions[$kode])) {
                $groupedTransactions[$kode] = [
                    'kode_transaksi' => $kode,
                    'tanggal_dan_waktu_pembelian' => $transaction->tanggal_dan_waktu_pembelian,
                    'status_pembayaran' => $transaction->status_pembayaran,
                    'status_pengiriman' => $transaction->status_pengiriman,
                    'metode_pembayaran' => $transaction->metode_pembayaran,
                    'jasa_pengiriman' => $transaction->jasa_pengiriman,
                    'total_transaksi' => 0,
                    'items' => []
                ];
            }
            $groupedTransactions[$kode]['items'][] = [
                'id_produk' => $transaction->id_produk,
                'nama_produk' => $transaction->nama_produk,
                'kode_produk' => $transaction->kode_produk,
                'harga_produk' => $transaction->harga_produk,
                'jumlah' => $transaction->jumlah,
                'total' => $transaction->total
            ];
            $groupedTransactions[$kode]['total_transaksi'] += $transaction->total;
        }

        // Convert to array and sort by date
        $groupedTransactions = array_values($groupedTransactions);
        usort($groupedTransactions, function($a, $b) {
            return strtotime($b['tanggal_dan_waktu_pembelian']) - strtotime($a['tanggal_dan_waktu_pembelian']);
        });

        $totalItems = DB::table('transactions')->where('id_user', session('userId'))->sum('jumlah');
        $totalPurchases = count($groupedTransactions);
        $addresses = CustomerAddress::where('id_user', session('userId'))->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'transactions' => $groupedTransactions,
                'totalItems' => $totalItems,
                'totalPurchases' => $totalPurchases,
                'addresses' => $addresses
            ]
        ], 200);
    }

    function exportTransactionsExcel(Request $request){
        if(!session('userLogin') || session('userRole') != 'admin'){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $filters = [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'payment_status' => $request->payment_status,
                'shipping_status' => $request->shipping_status
            ];

            return Excel::download(new TransactionsExport($filters), 'transaksi_' . date('Y-m-d_H-i-s') . '.xlsx');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    function exportTransactionsPdf(Request $request){
        if(!session('userLogin') || session('userRole') != 'admin'){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $query = Transactions::join('users', 'transactions.id_user', '=', 'users.id_user')
                ->join('customer_address', 'customer_address.id_alamat', '=', 'transactions.id_alamat')
                ->join('products', 'products.id_produk', '=', 'transactions.id_produk')
                ->select(
                    'transactions.kode_transaksi',
                    'transactions.tanggal_dan_waktu_pembelian',
                    'users.nama_tampilan as customer_name',
                    'users.email as customer_email',
                    'customer_address.alamat',
                    'customer_address.nama_kecamatan',
                    'customer_address.nama_kota',
                    'customer_address.nama_provinsi',
                    'customer_address.kode_pos',
                    'products.nama_produk',
                    'products.kode_produk',
                    'transactions.jumlah',
                    'transactions.total',
                    'transactions.metode_pembayaran',
                    'transactions.jasa_pengiriman',
                    'transactions.status_pembayaran',
                    'transactions.status_pengiriman'
                );

            // Apply date filters
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('transactions.tanggal_dan_waktu_pembelian', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('transactions.tanggal_dan_waktu_pembelian', '<=', $request->end_date);
            }

            // Apply payment status filter
            if ($request->has('payment_status') && $request->payment_status && $request->payment_status !== 'Semua Status Pembayaran') {
                $paymentStatusMapping = [
                    'unpaid' => 'Belum Bayar',
                    'pending' => 'Menunggu Verifikasi',
                    'paid' => 'Lunas',
                    'rejected' => 'Ditolak'
                ];

                if (isset($paymentStatusMapping[$request->payment_status])) {
                    $query->where('transactions.status_pembayaran', '=', $paymentStatusMapping[$request->payment_status]);
                }
            }

            // Apply shipping status filter
            if ($request->has('shipping_status') && $request->shipping_status && $request->shipping_status !== 'Semua Status Pengiriman') {
                $shippingStatusMapping = [
                    'belum_dikirim' => 'Belum Dikirim',
                    'sedang_dikirim' => 'Sedang Dikirim',
                    'diterima' => 'Diterima'
                ];

                if (isset($shippingStatusMapping[$request->shipping_status])) {
                    $query->where('transactions.status_pengiriman', '=', $shippingStatusMapping[$request->shipping_status]);
                }
            }

            $transactions = $query->orderBy('transactions.tanggal_dan_waktu_pembelian', 'desc')
                ->get();

            // Return HTML view directly - browser will handle PDF printing
            return view('admin.exports.transactions_pdf', compact('transactions'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
