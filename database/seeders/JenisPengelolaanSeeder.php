<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPengelolaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kode' => 'JP-01',
                'nama_jenis' => 'Pemanfaatan BMN',
                'deskripsi' => 'Penggunaan BMN oleh pihak lain dalam jangka waktu tertentu',
                'target_hari' => 14,
                'kategori' => 'pemanfaatan',
                'status' => 'aktif',
            ],
            [
                'kode' => 'JP-02',
                'nama_jenis' => 'Pemindahtanganan BMN',
                'deskripsi' => 'Pengalihan kepemilikan BMN',
                'target_hari' => 21,
                'kategori' => 'pemindahtanganan',
                'status' => 'aktif',
            ],
            [
                'kode' => 'JP-03',
                'nama_jenis' => 'Penghapusan BMN',
                'deskripsi' => 'Penghapusan BMN dari daftar barang',
                'target_hari' => 30,
                'kategori' => 'penghapusan',
                'status' => 'aktif',
            ],
            [
                'kode' => 'JP-04',
                'nama_jenis' => 'Sewa BMN',
                'deskripsi' => 'Penyewaan BMN kepada pihak ketiga',
                'target_hari' => 14,
                'kategori' => 'sewa',
                'status' => 'aktif',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\JenisPengelolaan::create($item);
        }
    }
}
