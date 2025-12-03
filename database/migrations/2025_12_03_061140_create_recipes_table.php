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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('station', ['unit_foundry', 'gear_forge', 'essence_vault']);
            $table->json('inputs'); // material/fragment/core requirements
            $table->json('outputs'); // forged unit, gear, or material outputs
            $table->foreignId('required_blueprint_id')->nullable()->constrained('blueprints')->nullOnDelete();
            $table->boolean('requires_core_item')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['name', 'station']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
