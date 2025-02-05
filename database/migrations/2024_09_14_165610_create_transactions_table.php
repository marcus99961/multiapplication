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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id');
            $table->foreignId('item_id');
            $table->string('group_code');  
            $table->string('transaction_no');           
            $table->date('date');           
            $table->string('action');
            $table->string('currency');
            $table->float('in_qty')->default(0);
            $table->float('out_qty')->default(0);
            $table->float('in_usd')->default(0);
            $table->float('in_mmk')->default(0);
            $table->float('out_usd')->default(0);
            $table->float('out_mmk')->default(0);                            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
