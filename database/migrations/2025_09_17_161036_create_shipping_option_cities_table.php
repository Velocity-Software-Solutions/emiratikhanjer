<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_option_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_option_id')->constrained('shipping_options')->onDelete('cascade');
            $table->string('city');
            $table->timestamps();
            $table->unique(['shipping_option_id', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_option_cities');
    }
};
