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
        Schema::create('issueitems', function (Blueprint $table) {
            $table->id();
            $table->string('issue_no');
            $table->date('issue_date');
            $table->foreignId('location_id');           
            $table->unsignedBigInteger('department_id');          
            $table->foreignId('item_id');           
            $table->float('qty');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issueitems');
    }
};
