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
        Schema::create('game_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attacker_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('defender_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('winner_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('winner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('rating_change')->default(0); // applied to winner
            $table->unsignedInteger('attacker_rating_before')->default(1200);
            $table->unsignedInteger('defender_rating_before')->default(1200);
            $table->unsignedInteger('attacker_rating_after')->default(1200);
            $table->unsignedInteger('defender_rating_after')->default(1200);
            $table->timestamp('played_at')->useCurrent();
            $table->timestamps();

            $table->index(['winner_team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_matches');
    }
};
