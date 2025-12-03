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
        Schema::create('evolutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('from_tier');
            $table->unsignedTinyInteger('to_tier');
            $table->json('required_materials')->nullable();
            $table->unsignedInteger('required_wins')->default(0);
            $table->json('stat_modifiers')->nullable();
            $table->string('new_name')->nullable();
            $table->string('passive_trait')->nullable();
            $table->timestamps();

            $table->unique(['from_tier', 'to_tier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evolutions');
    }
};
