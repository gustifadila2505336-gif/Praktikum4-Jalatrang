<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
{
    // Urutan penting: kategori dan tag harus ada sebelum berita
    $this->call([
        KategoriSeeder::class,
        TagSeeder::class,
        BeritaSeeder::class,
    ]);
}

}
