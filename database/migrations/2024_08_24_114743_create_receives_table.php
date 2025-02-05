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
        Schema::create('receives', function (Blueprint $table) {
       
                $table->id();
                $table->string('invoice_no');
                $table->date('received_date');
                $table->foreignId('location_id');
                $table->foreignId('supplier_id');
                $table->string('currency');
                $table->foreignId('item_id');
                $table->float('price_usd');
                $table->float('price_mmk');
                $table->float('qty');
                $table->date('expired_date')->nullable();    
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receives');
    }
};
