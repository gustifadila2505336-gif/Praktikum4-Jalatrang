<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Support\Arr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request)
{
    $beritas = Berita::with(['kategori', 'tags'])
        ->when($request->kata_kunci, function ($q, $kata) {
            $q->where('judul', 'like', "%{$kata}%");
        })
        ->when($request->kategori, function ($q, $id) {
            $q->where('kategori_id', $id);
        })
        ->when($request->tag, function ($q, $id) {
            $q->whereHas('tags', fn ($t) => $t->where('tags.id', $id));
        })
        ->latest()
        ->paginate(6)
        ->withQueryString();
 
    $kategoris = Kategori::orderBy('nama')->get();
 
    return view('berita.index', compact('beritas', 'kategoris'));
}
 
public function create()
{
    return view('berita.create', [
        'kategoris' => Kategori::orderBy('nama')->get(),
        'tags'      => Tag::orderBy('nama')->get(),
    ]);
}
 
public function store(Request $request)
{
    $data = $request->validate([
        'judul'       => 'required|max:255',
        'kategori_id' => 'required|exists:kategoris,id',
        'isi'         => 'required|min:10',
        'tags'        => 'nullable|array',
        'tags.*'      => 'exists:tags,id',
    ]);
 
    $berita = Berita::create(Arr::except($data, 'tags'));
    $berita->tags()->sync($data['tags'] ?? []);
 
    return redirect()->route('berita.index')
        ->with('success', 'Berita berhasil ditambahkan!');
}
 
public function show(Berita $berita)
{
    $berita->increment('dilihat');
    $berita->load(['kategori', 'tags']);
 
    return view('berita.show', compact('berita'));
}
 
public function edit(Berita $berita)
{
    return view('berita.edit', [
        'berita'    => $berita->load('tags'),
        'kategoris' => Kategori::orderBy('nama')->get(),
        'tags'      => Tag::orderBy('nama')->get(),
    ]);
}
 
public function update(Request $request, Berita $berita)
{
    $data = $request->validate([
        'judul'       => 'required|max:255',
        'kategori_id' => 'required|exists:kategoris,id',
        'isi'         => 'required|min:10',
        'tags'        => 'nullable|array',
        'tags.*'      => 'exists:tags,id',
    ]);
 
    $berita->update(Arr::except($data, 'tags'));
    $berita->tags()->sync($data['tags'] ?? []);
 
    return redirect()->route('berita.index')
        ->with('success', 'Berita berhasil diperbarui!');
}

    public function destroy(Berita $berita): RedirectResponse
    {
        $berita->delete();

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dihapus!');
    }

    /**
     * @return array<string, mixed>
     */
    private function validasi(Request $request): array
    {
        return $request->validate([
            'judul' => 'required|max:255',
            'kategori' => ['required', Rule::in(Berita::KATEGORI)],
            'isi' => 'required|min:10',
            'penulis' => 'required|max:100',
            'tanggal' => 'required|date',
            'tags' => 'nullable|max:255',
            'gambar' => 'nullable|image|max:2048',
        ]);
    }

    /**
     * Pindahkan file gambar ke public/images/berita dan simpan nama filenya saja.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function simpanGambar(Request $request, array $data): array
    {
        unset($data['gambar']);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $nama = time().'-'.$file->hashName();
            $file->move(public_path('images/berita'), $nama);
            $data['gambar'] = $nama;
        }

        return $data;
    }
}
