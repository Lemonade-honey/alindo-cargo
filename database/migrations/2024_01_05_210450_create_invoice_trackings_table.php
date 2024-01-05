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
        Schema::create('invoice_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_invoice')->unique()->constrained('invoices');
            $table->uuid('tracking_guid');
            $table->text('tracking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_trackings');
    }
};
