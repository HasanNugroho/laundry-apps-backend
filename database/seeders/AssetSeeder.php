<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            [
                'status' => 'ANTRIAN',
                'type' => 'pesanan'
            ],
            [
                'status' => 'PROSES',
                'type' => 'pesanan'
            ],
            [
                'status' => 'PACKING',
                'type' => 'pesanan'
            ],
            [
                'status' => 'SELESAI',
                'type' => 'pesanan'
            ],
            [
                'status' => 'DIBATALKAN',
                'type' => 'pesanan'
            ],
            [
                'status' => 'LUNAS',
                'type' => 'pembayaran'
            ],
            [
                'status' => 'BELUM LUNAS',
                'type' => 'pembayaran'
            ],
           
        ];
        DB::table('asset_statuses')->insert($status);
    }
}
