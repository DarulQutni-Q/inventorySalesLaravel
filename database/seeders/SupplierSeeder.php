<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'name' => 'UD Berkah Jaya',
            'phone' => '0812-3456-7890',
            'email' => 'berkah.jaya@example.com',
            'address' => 'Jl. Pasar Induk No. 12, Jakarta Timur',
        ]);

        Supplier::create([
            'name' => 'PT Sinar Makmur',
            'phone' => '0813-9876-5432',
            'email' => 'sales@sinarmakmur.example.com',
            'address' => 'Jl. Raya Bekasi KM 21, Cakung',
        ]);

        Supplier::create([
            'name' => 'Toko Kelontong Barokah',
            'phone' => '0857-1122-3344',
            'email' => null,
            'address' => 'Jl. Pahlawan No. 8, Semarang',
        ]);
    }
}
