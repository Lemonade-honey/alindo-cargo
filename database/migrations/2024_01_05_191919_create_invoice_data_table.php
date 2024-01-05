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
        Schema::create('invoice_datas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_invoice')->unique()->constrained('invoices');
            $table->text('form_setting');
            $table->string('berat');
            $table->string('harga_kg');
            $table->string('kategori');
            $table->boolean('pemeriksaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_datas');
    }
};
