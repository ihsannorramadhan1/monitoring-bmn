<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing data to start fresh (Compatible with Postgres & MySQL)
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Satker::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $faker = \Faker\Factory::create('id_ID');

        $satkers = [
            'Kanwil Kemenkumham Kalimantan Selatan',
            'Lapas Kelas IIA Banjarmasin',
            'Lapas Narkotika Kelas IIA Karang Intan',
            'Rutan Kelas IIB Barabai',
            'Rutan Kelas IIB Kandangan',
            'Rutan Kelas IIB Pelaihari',
            'Rutan Kelas IIB Marabahan',
            'Kantor Imigrasi Kelas I TPI Banjarmasin',
            'Polda Kalimantan Selatan',
            'Polresta Banjarmasin',
            'Polres Banjar',
            'Polres Barito Kuala',
            'Polres Hulu Sungai Tengah',
            'Polres Hulu Sungai Selatan',
            'Polres Hulu Sungai Utara',
            'Polres Tabalong',
            'Polres Balangan',
            'Polres Tanah Laut',
            'Polres Tanah Bumbu',
            'Polres Kotabaru',
            'Kejaksaan Tinggi Kalimantan Selatan',
            'Kejaksaan Negeri Banjarmasin',
            'Kejaksaan Negeri Banjar',
            'Kejaksaan Negeri Barito Kuala',
            'Kejaksaan Negeri Hulu Sungai Tengah',
            'Kejaksaan Negeri Hulu Sungai Selatan',
            'Kejaksaan Negeri Hulu Sungai Utara',
            'Kejaksaan Negeri Tabalong',
            'Kejaksaan Negeri Balangan',
            'Kejaksaan Negeri Tanah Laut',
            'Kejaksaan Negeri Tanah Bumbu',
            'Kejaksaan Negeri Kotabaru',
            'Pengadilan Negeri Banjarmasin',
            'Pengadilan Negeri Martapura',
            'Pengadilan Negeri Marabahan',
            'Pengadilan Negeri Kandangan',
            'Pengadilan Negeri Barabai',
            'Pengadilan Negeri Amuntai',
            'Pengadilan Negeri Tanjung',
            'Pengadilan Negeri Pelaihari',
            'Pengadilan Negeri Batulicin',
            'Pengadilan Negeri Kotabaru',
            'Pengadilan Agama Banjarmasin',
            'Pengadilan Agama Martapura',
            'Pengadilan Agama Marabahan',
            'Pengadilan Agama Kandangan',
            'Pengadilan Agama Barabai',
            'Pengadilan Agama Amuntai',
            'Pengadilan Agama Tanjung',
            'Pengadilan Agama Pelaihari',
            'Pengadilan Agama Batulicin',
            'Pengadilan Agama Kotabaru',
            'KPP Pratama Banjarmasin',
            'KPP Pratama Banjarbaru',
            'KPPN Banjarmasin',
            'Kantor Wilayah DJP Kalimantan Selatan',
            'Kantor Bea dan Cukai Banjarmasin',
            'BPN Provinsi Kalimantan Selatan',
            'Balai Besar Wilayah Sungai Kalimantan III',
            'Balai Karantina Pertanian Banjarmasin',
            'UPT Kementerian Perhubungan Kalimantan Selatan',
            'UPT Kementerian PUPR Kalimantan Selatan',
        ];

        foreach ($satkers as $namaSatker) {
            $instansiInduk = $this->getInstansiInduk($namaSatker);

            \App\Models\Satker::create([
                'kode_satker' => $faker->unique()->numerify('SATKER-####'),
                'nama_satker' => $namaSatker,
                'instansi_induk' => $instansiInduk,
                'alamat' => $faker->address,
                'pic_nama' => $faker->name,
                'pic_kontak' => $faker->phoneNumber,
                'email' => $faker->unique()->companyEmail,
                'status' => 'aktif',
            ]);
        }
    }

    private function getInstansiInduk($nama)
    {
        $nama = strtolower($nama);

        if (str_contains($nama, 'kemenkumham') || str_contains($nama, 'lapas') || str_contains($nama, 'rutan') || str_contains($nama, 'imigrasi')) {
            return 'Kementerian Hukum dan HAM';
        }
        if (str_contains($nama, 'polda') || str_contains($nama, 'polres') || str_contains($nama, 'polri')) {
            return 'Kepolisian Negara Republik Indonesia';
        }
        if (str_contains($nama, 'kejaksaan')) {
            return 'Kejaksaan Republik Indonesia';
        }
        if (str_contains($nama, 'pengadilan')) {
            return 'Mahkamah Agung Republik Indonesia';
        }
        if (str_contains($nama, 'kpp') || str_contains($nama, 'djp') || str_contains($nama, 'bea dan cukai') || str_contains($nama, 'kppn')) {
            return 'Kementerian Keuangan';
        }
        if (str_contains($nama, 'bpn')) {
            return 'Kementerian ATR/BPN';
        }
        if (str_contains($nama, 'perhubungan')) {
            return 'Kementerian Perhubungan';
        }
        if (str_contains($nama, 'pupr') || str_contains($nama, 'sungai')) {
            return 'Kementerian PUPR';
        }
        if (str_contains($nama, 'pertanian') || str_contains($nama, 'karantina')) {
            return 'Kementerian Pertanian';
        }

        return 'Kementerian Lainnya';
    }
}
