<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // firstOrCreate berdasarkan telepon supaya seeding ulang tidak
        // membuat duplikat, sama seperti aturan form masuk pelanggan.
        $customers = [
            ['name' => 'Toko Sembako Bu Ani', 'phone' => '0812-1111-2222', 'address' => 'Jl. Melati No. 4, Depok'],
            ['name' => 'Warung Pak Budi', 'phone' => '0813-3333-4444', 'address' => 'Jl. Kenanga No. 17, Bekasi'],
            ['name' => 'Toko Kelontong Sari', 'phone' => '0857-5555-6666', 'address' => 'Jl. Anggrek No. 2, Bogor'],
            ['name' => 'Warung Makan Barokah', 'phone' => '0878-7777-8888', 'address' => 'Jl. Cempaka No. 9, Tanggerang'],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['phone' => $customer['phone']], $customer);
        }
    }
}
