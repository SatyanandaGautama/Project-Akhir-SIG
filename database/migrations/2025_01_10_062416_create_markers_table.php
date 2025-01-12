<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->decimal('latitude', 10, 7); 
            $table->decimal('longitude', 10, 7); 
            $table->string('layanan_kesehatan'); 
            $table->string('jam_operasional'); 
            $table->string('no_telpon'); 
            $table->string('alamat'); 
            $table->string('foto');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markers');
    }
};
