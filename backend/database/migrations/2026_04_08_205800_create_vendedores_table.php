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
        Schema::create('vendedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('website')->nullable();

            $table->string('calle')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();

            $table->string('empresa_nombre')->nullable();

            $table->foreignId('lote_id')->nullable()->constrained('lotes')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendedores');
    }
};