<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenans', function (Blueprint $table) {
            $table->increments('idtenans');
            $table->string('nama', 45)->notNull();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenans');
    }
};
