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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->string('name');
            $table->string('unit_measure');
            $table->string('spec')->nullable();
            $table->string('group_code');
            $table->float('min_qty')->nullable();
            $table->float('max_qty')->nullable();
            $table->float('reorder_level')->nullable();          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
