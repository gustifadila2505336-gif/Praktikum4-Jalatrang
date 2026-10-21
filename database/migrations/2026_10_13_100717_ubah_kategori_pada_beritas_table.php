<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('beritas', function (Blueprint $table) {
        $table->dropColumn('kategori');
        $table->foreignId('kategori_id')->after('judul')
              ->constrained('kategoris');
    });
}
 
public function down(): void
{
    Schema::table('beritas', function (Blueprint $table) {
        $table->dropConstrainedForeignId('kategori_id');
        $table->string('kategori')->after('judul');
    });
}

};
