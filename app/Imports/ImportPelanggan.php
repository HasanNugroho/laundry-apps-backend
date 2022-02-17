<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPelanggan implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $uuid = Str::uuid();
        $user_outlet = Auth::user()->outlet_id;
        return new Pelanggan([
        'id' => $uuid,
        'nama' => $row['nama'],
        'alamat' => $row['alamat'],
        'outletid' => $user_outlet,
        'whatsapp' => $row['whatsapp'],
        ]);
    }
}
