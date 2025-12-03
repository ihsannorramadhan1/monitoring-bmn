<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@kpknl.go.id',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'nip' => '199001012020011001',
                'divisi' => 'Subbagian Umum',
                'status' => 'aktif',
            ],
            [
                'name' => 'Staff BMN',
                'email' => 'staff@kpknl.go.id',
                'password' => bcrypt('password'),
                'role' => 'staff',
                'nip' => '199505052022031002',
                'divisi' => 'Seksi PKN',
                'status' => 'aktif',
            ],
            [
                'name' => 'Viewer User',
                'email' => 'viewer@kpknl.go.id',
                'password' => bcrypt('password'),
                'role' => 'viewer',
                'nip' => '199808082023011003',
                'divisi' => 'Seksi HI',
                'status' => 'aktif',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
