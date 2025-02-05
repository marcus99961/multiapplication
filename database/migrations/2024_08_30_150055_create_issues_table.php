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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->date('received_date');
            $table->string('issue_no');
            $table->date('issue_date');
            $table->foreignId('location_id');
            $table->foreignId('supplier_id');
            $table->unsignedBigInteger('department_id');
            $table->string('currency');
            $table->foreignId('item_id');
            $table->float('price_usd');
            $table->float('price_mmk');
            $table->float('qty');
            $table->date('expired_date')->nullable();  
            $table->foreignId('stock_id');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
