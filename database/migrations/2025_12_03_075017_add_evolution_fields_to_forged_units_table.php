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
        Schema::table('forged_units', function (Blueprint $table) {
            $table->unsignedTinyInteger('tier')->default(1)->after('metadata');
            $table->string('variant_name')->nullable()->after('tier');
            $table->string('passive_trait')->nullable()->after('variant_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forged_units', function (Blueprint $table) {
            $table->dropColumn(['tier', 'variant_name', 'passive_trait']);
        });
    }
};
