<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MstUnor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => 'Superadmin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('password')
            ]);
            $user->assignRole('superadmin');


            // Unor Sekre
            $sekre = MstUnor::create([
                'unor_code' => 'sekre',
                'unor_name' => 'Sekretariat',
            ]);

            $user = User::create([
                'name' => 'Subbagian Kepegawaian, Umum, dan Kehumasan',
                'email' => 'kepegumhum@mail.com',
                'password' => bcrypt('s1p3k4'),
                'unor_id' => $sekre->id,
            ]);
            $user->assignRole('coordinator');


            $produksi = MstUnor::create([
                'unor_code' => 'produksi',
                'unor_name' => 'Bidang Produksi',
            ]);
            $user = User::create([
                'name' => 'Bidang Produksi',
                'email' => 'produksi@mail.com',
                'password' => bcrypt('s1p3k4'),
                'unor_id' => $produksi->id,
            ]);
            $user->assignRole('coordinator');

            DB::commit();
        } catch (\Throwable $e) {
            dd($e);

            DB::rollback();
        }
    }
}
