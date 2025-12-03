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
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            \App\Models\Satker::create([
                'kode_satker' => $faker->unique()->numerify('SATKER-####'),
                'nama_satker' => 'Kantor ' . $faker->company,
                'instansi_induk' => 'Kementerian Keuangan',
                'alamat' => $faker->address,
                'pic_nama' => $faker->name,
                'pic_kontak' => $faker->phoneNumber,
                'email' => $faker->companyEmail,
                'status' => 'aktif',
            ]);
        }
    }
}
