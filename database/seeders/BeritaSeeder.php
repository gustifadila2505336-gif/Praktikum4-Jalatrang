<?php
 
namespace Database\Seeders;
 
use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Database\Seeder;
 
class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $olahraga   = Kategori::where('nama', 'Olahraga')->first();
        $pendidikan = Kategori::where('nama', 'Pendidikan')->first();
        $potensi    = Kategori::where('nama', 'Potensi')->first();
 
        // Membuat berita lewat relasi: kategori_id terisi otomatis
        $b1 = $olahraga->beritas()->create([
            'judul' => 'Turnamen Voli Antar Dusun Resmi Dibuka',
            'isi'   => 'Turnamen voli tahunan antar dusun resmi dibuka oleh kepala desa...',
        ]);
        $b2 = $pendidikan->beritas()->create([
            'judul' => 'Sosialisasi Bank Sampah untuk Warga',
            'isi'   => 'Pemerintah desa mengadakan sosialisasi pengelolaan sampah...',
        ]);
        $b3 = $potensi->beritas()->create([
            'judul' => 'Panen Raya Kelompok Wanita Tani',
            'isi'   => 'Kelompok wanita tani desa berhasil panen sayuran organik...',
        ]);
 
        // Menempelkan tag ke berita (many to many) lewat tabel pivot
        $b1->tags()->attach(Tag::whereIn('nama', ['desa', 'kegiatan'])->pluck('id'));
        $b2->tags()->attach(Tag::whereIn('nama', ['warga', 'lingkungan'])->pluck('id'));
        $b3->tags()->attach(Tag::whereIn('nama', ['desa', 'ekonomi'])->pluck('id'));
    }
}
