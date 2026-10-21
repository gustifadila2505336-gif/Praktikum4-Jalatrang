<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
 
class Berita extends Model
{
    protected $fillable = ['judul', 'kategori_id', 'gambar', 'isi', 'penulis'];
 
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
 
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
