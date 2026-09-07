<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gobogs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unik')->unique();
            $table->double('nilai');
            $table->string('foto')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('kode_enkripsi');
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gobogs');
    }
};
