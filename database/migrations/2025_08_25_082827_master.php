<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('email');
            $table->text('password');
            $table->string('nama_tampilan');
            $table->string('nomor_whatsapp');
            $table->string('role');
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_token_expires')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->string('kode_produk');
            $table->integer('id_kategori');
            $table->string('nama_produk');
            $table->string('slug_produk');
            $table->integer('stok_produk');
            $table->text('detail_produk');
            $table->integer('harga_produk');
            $table->text('gambar_produk');
            $table->timestamps();
        });

        Schema::create('product_image_gallery', function (Blueprint $table) {
            $table->increments('pi_id');
            $table->integer('id_produk');
            $table->text('gambar_produk')->nullable();
            $table->timestamps();
        });

        Schema::create('product_category', function (Blueprint $table) {
            $table->increments('id_kategori');
            $table->string('nama_kategori');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id_transaksi');
            $table->string('kode_transaksi');
            $table->integer('id_produk');
            $table->integer('id_user');
            $table->integer('jumlah');
            $table->integer('total');
            $table->datetime('tanggal_dan_waktu_pembelian');
            $table->string('jasa_pengiriman')->nullable();
            $table->integer('id_alamat')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->string('status_pengiriman')->nullable();
            $table->text('bukti_pembayaran')->nullable();
            $table;
            $table->timestamps();
        });

        Schema::create('customer_address', function (Blueprint $table) {
            $table->increments('id_alamat');
            $table->integer('id_user');
            $table->string('alamat');
            $table->string('nama_kecamatan');
            $table->string('nama_kota');
            $table->string('nama_provinsi');
            $table->string('kode_pos')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id_keranjang');
            $table->integer('id_user');
            $table->integer('id_produk');
            $table->integer('jumlah');
            $table->timestamps();
        });

        Schema::create('bank_account', function (Blueprint $table) {
            $table->increments('id_akun_bank');
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->datetime('tanggal_ditambahkan');
            $table->enum('aktif',['ya','tidak'])->default('tidak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_address');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('product_image_gallery');
        Schema::dropIfExists('bank_account');
    }
};
