<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan_tenans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('gobogs_id');
            $table->tinyInteger('valid_status');
            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('gobogs_id')->references('id')->on('gobogs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_tenans');
    }
};
