@extends('layouts.app')

@section('title', 'Berita Desa')

@section('hero')
<div class="page-hero" style="padding:60px 0 40px">
    <div class="container text-white">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('berita.index') }}" class="text-warning text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Berita</li>
            </ol>
        </nav>
        <h1 class="teko fw-bold mb-0" style="font-size:2.5rem">Berita &amp; Informasi</h1>
        <p style="opacity:0.7">Informasi terkini dari Desa Jalatrang</p>
    </div>
</div>
@endsection

@section('content')
<div class="row g-4">

    <div class="col-lg-3">
        <div class="sticky-top" style="top:80px;z-index:1">
            <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden">
                <div class="card-header text-white fw-bold bg-desa">
                    <i class="fas fa-filter me-2"></i> Filter Berita
                </div>
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('berita.index') }}">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kata Kunci</label>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari berita...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kategori</label>
                            <select name="kategori" class="form-select mb-3">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $k)
                                <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                            <a href="{{ route('berita.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <a href="{{ route('berita.create') }}" class="btn btn-sm btn-success w-100 mt-3">
                <i class="fas fa-plus me-1"></i> Tambah Berita
            </a>
        </div>
    </div>

    <div class="col-lg-9">
        @if ($beritas->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-newspaper fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Berita tidak ditemukan.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach ($beritas as $berita)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-berita border-0 shadow-sm h-100">
                            <div class="thumb">
                                <img src="{{ $berita->gambar_url }}" class="w-100 h-100" alt="{{ $berita->judul }}"
                                     onerror="this.onerror=null;this.src='{{ asset('images/no_image.jpg') }}'">
                            </div>
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge badge-kategori text-white">{{ $berita->kategori->nama }}</span>
                                    <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i>{{ $berita->tanggal->locale('id')->translatedFormat('j M Y') }}</small>
                                </div>
                                <h6 class="fw-bold mb-2" style="line-height:1.3;flex:1">
                                    <a href="{{ route('berita.show', $berita) }}" class="text-decoration-none text-dark">{{ Str::limit($berita->judul, 70) }}</a>
                                </h6>
                                <p class="text-muted small mb-2">{{ $berita->ringkasan }}</p>
                                @if ($berita->daftar_tag)
                                    <div class="mb-3">
                                        @foreach (array_slice($berita->daftar_tag, 0, 3) as $tag)
                                            <a href="{{ route('berita.index', ['q' => $tag]) }}" class="badge bg-light text-secondary border me-1 text-decoration-none small fw-normal" style="font-size:0.68rem">
                                                #{{ $tag }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="d-flex align-items-center justify-content-between mt-auto">
                                    <small class="text-muted"><i class="fas fa-eye me-1"></i>{{ number_format($berita->dilihat) }}</small>
                                    <a href="{{ route('berita.show', $berita) }}" class="btn btn-sm btn-outline-primary" style="font-size:0.75rem">Baca <i class="fas fa-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $beritas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
