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
        Schema::create('transmutation_recipes', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->string('input_material_name');
            $table->unsignedInteger('input_quantity');
            $table->string('output_material_name');
            $table->unsignedInteger('output_quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transmutation_recipes');
    }
};
