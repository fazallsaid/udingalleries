<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk = [
            ['kode_produk' => 'ART-001', 'id_kategori' => 1, 'nama_produk' => 'Lukisan Pemandangan Gunung Bromo', 'slug_produk' => 'lukisan-pemandangan-gunung-bromo', 'stok_produk' => 5, 'detail_produk' => 'Lukisan cat minyak di atas kanvas berukuran 120x80 cm. Menggambarkan keindahan matahari terbit di Gunung Bromo. Karya seniman lokal Probolinggo.', 'harga_produk' => 2500000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-002', 'id_kategori' => 2, 'nama_produk' => 'Ukiran Naga Kayu Jati Jepara', 'slug_produk' => 'ukiran-naga-kayu-jati-jepara', 'stok_produk' => 3, 'detail_produk' => 'Ukiran detail naga dari kayu jati asli Jepara. Dikerjakan secara handmade dengan tingkat presisi tinggi. Ukuran 50x100 cm.', 'harga_produk' => 4500000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-003', 'id_kategori' => 3, 'nama_produk' => 'Patung Penari Bali Abstrak', 'slug_produk' => 'patung-penari-bali-abstrak', 'stok_produk' => 10, 'detail_produk' => 'Patung dari kayu suar dengan finishing halus. Bentuk abstrak modern dari penari Bali. Tinggi 60 cm.', 'harga_produk' => 850000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-004', 'id_kategori' => 4, 'nama_produk' => 'Keranjang Anyaman Rotan Kalimantan', 'slug_produk' => 'keranjang-anyaman-rotan-kalimantan', 'stok_produk' => 25, 'detail_produk' => 'Keranjang serbaguna dari anyaman rotan asli Kalimantan. Kuat dan memiliki corak etnik yang khas. Diameter 40 cm.', 'harga_produk' => 350000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-005', 'id_kategori' => 5, 'nama_produk' => 'Kain Batik Tulis Motif Mega Mendung', 'slug_produk' => 'kain-batik-tulis-motif-mega-mendung', 'stok_produk' => 15, 'detail_produk' => 'Kain batik tulis Cirebon asli dari katun primisima. Motif Mega Mendung klasik dengan pewarnaan alami. Ukuran 2.5 x 1.15 meter.', 'harga_produk' => 1200000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-006', 'id_kategori' => 1, 'nama_produk' => 'Lukisan Abstrak Kontemporer Merah', 'slug_produk' => 'lukisan-abstrak-kontemporer-merah', 'stok_produk' => 8, 'detail_produk' => 'Lukisan akrilik dengan goresan ekspresif dominan warna merah dan emas. Ukuran 100x100 cm, cocok untuk interior modern.', 'harga_produk' => 1800000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-007', 'id_kategori' => 2, 'nama_produk' => 'Topeng Panji Kayu Cendana', 'slug_produk' => 'topeng-panji-kayu-cendana', 'stok_produk' => 12, 'detail_produk' => 'Topeng karakter Panji dalam kesenian Jawa. Terbuat dari kayu cendana wangi dengan ukiran dan lukisan tangan yang detail.', 'harga_produk' => 750000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-008', 'id_kategori' => 3, 'nama_produk' => 'Patung Ganesha Batu Andesit', 'slug_produk' => 'patung-ganesha-batu-andesit', 'stok_produk' => 4, 'detail_produk' => 'Patung Ganesha yang dipahat dari batu andesit solid. Cocok untuk dekorasi taman atau ruang meditasi. Tinggi 80 cm.', 'harga_produk' => 3200000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-009', 'id_kategori' => 4, 'nama_produk' => 'Vas Bunga Keramik Kasongan', 'slug_produk' => 'vas-bunga-keramik-kasongan', 'stok_produk' => 30, 'detail_produk' => 'Vas bunga keramik buatan tangan dari sentra kerajinan Kasongan, Yogyakarta. Glasir berwarna toska dengan motif retak.', 'harga_produk' => 275000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-010', 'id_kategori' => 5, 'nama_produk' => 'Tenun Ikat Sumba Motif Kuda', 'slug_produk' => 'tenun-ikat-sumba-motif-kuda', 'stok_produk' => 7, 'detail_produk' => 'Kain tenun ikat asli dari Sumba Timur. Dibuat dengan pewarna alami dan menampilkan motif kuda yang ikonik. Proses pembuatan memakan waktu berbulan-bulan.', 'harga_produk' => 5500000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-011', 'id_kategori' => 1, 'nama_produk' => 'Lukisan Ikan Koi Sembilan Ekor', 'slug_produk' => 'lukisan-ikan-koi-sembilan-ekor', 'stok_produk' => 6, 'detail_produk' => 'Lukisan fengshui sembilan ikan koi yang dipercaya membawa keberuntungan. Cat minyak di kanvas 150x90 cm.', 'harga_produk' => 2200000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-012', 'id_kategori' => 2, 'nama_produk' => 'Relief Ramayana Kayu Mahoni', 'slug_produk' => 'relief-ramayana-kayu-mahoni', 'stok_produk' => 2, 'detail_produk' => 'Panel relief 3D cerita Ramayana dari kayu mahoni. Ukuran besar 200x100 cm, cocok untuk hiasan dinding pendopo.', 'harga_produk' => 8000000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-013', 'id_kategori' => 3, 'nama_produk' => 'Patung Burung Cendrawasih Perunggu', 'slug_produk' => 'patung-burung-cendrawasih-perunggu', 'stok_produk' => 5, 'detail_produk' => 'Patung dari bahan perunggu solid dengan detail bulu yang indah. Menggambarkan keanggunan burung Cendrawasih dari Papua.', 'harga_produk' => 4100000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-014', 'id_kategori' => 4, 'nama_produk' => 'Wayang Golek Cepot Kayu Albasia', 'slug_produk' => 'wayang-golek-cepot-kayu-albasia', 'stok_produk' => 20, 'detail_produk' => 'Wayang golek karakter Cepot khas Sunda. Terbuat dari kayu albasia ringan dengan pakaian batik.', 'harga_produk' => 175000, 'gambar_produk' => ''],
            ['kode_produk' => 'ART-015', 'id_kategori' => 5, 'nama_produk' => 'Songket Palembang Benang Emas', 'slug_produk' => 'songket-palembang-benang-emas', 'stok_produk' => 4, 'detail_produk' => 'Kain songket Palembang asli ditenun dengan benang emas. Motif cantik dan mewah, cocok untuk acara adat.', 'harga_produk' => 6500000, 'gambar_produk' => '']
        ];

        $dataToInsert = array_map(function ($item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            return $item;
        }, $produk);

        Products::insert($dataToInsert);
    }
}
