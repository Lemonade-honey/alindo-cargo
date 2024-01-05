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
        Schema::create('invoice_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_invoice')->unique()->constrained('invoices');
            $table->string('biaya_kirim');
            $table->text('biaya_lainnya')->nullable();
            $table->string('biaya_total');
            $table->string('status')->default('Belum Bayar');
            $table->string('metode')->nullable();
            $table->string('bukti')->nullable();
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_costs');
    }
};
