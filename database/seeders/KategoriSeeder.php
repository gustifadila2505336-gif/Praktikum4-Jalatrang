<?php
 
namespace Database\Seeders;
 
use App\Models\Kategori;
use Illuminate\Database\Seeder;
 
class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Olahraga', 'Pendidikan', 'Potensi', 'Pembangunan'] as $nama) {
            Kategori::create(['nama' => $nama]);
        }
    }
}
