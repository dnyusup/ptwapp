<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KontraktorList;

class KontraktorListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kontraktors = [
            [
                'company_name' => 'PT Sejahtera Konstruksi',
                'company_code' => 'SEJ001',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'phone' => '021-12345678',
                'email' => 'info@sejahterakonstruksi.co.id',
                'contact_person' => 'Budi Santoso',
                'is_active' => true,
            ],
            [
                'company_name' => 'CV Mitra Teknik',
                'company_code' => 'MIT002',
                'address' => 'Jl. Gatot Subroto No. 456, Jakarta Selatan',
                'phone' => '021-87654321',
                'email' => 'admin@mitrateknik.co.id',
                'contact_person' => 'Sari Indrawati',
                'is_active' => true,
            ],
            [
                'company_name' => 'PT Karya Utama Engineering',
                'company_code' => 'KUE003',
                'address' => 'Jl. HR Rasuna Said No. 789, Jakarta Selatan',
                'phone' => '021-11223344',
                'email' => 'contact@karyautama.co.id',
                'contact_person' => 'Ahmad Wijaya',
                'is_active' => true,
            ],
            [
                'company_name' => 'PT Bangun Jaya Mandiri',
                'company_code' => 'BJM004',
                'address' => 'Jl. Thamrin No. 321, Jakarta Pusat',
                'phone' => '021-55667788',
                'email' => 'info@bangunjaya.co.id',
                'contact_person' => 'Dewi Kusuma',
                'is_active' => true,
            ],
            [
                'company_name' => 'CV Teknologi Maju',
                'company_code' => 'TEM005',
                'address' => 'Jl. Casablanca No. 654, Jakarta Selatan',
                'phone' => '021-99887766',
                'email' => 'admin@teknologimaju.co.id',
                'contact_person' => 'Rudi Hartono',
                'is_active' => false, // Inactive example
            ]
        ];

        foreach ($kontraktors as $kontraktor) {
            KontraktorList::create($kontraktor);
        }
    }
}
