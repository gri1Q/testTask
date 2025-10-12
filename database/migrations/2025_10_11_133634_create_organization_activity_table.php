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
        Schema::create('organization_activity', function (Blueprint $table) {
            $table->foreignId('organization_id')
                ->comment('ID организации')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->foreignId('activity_id')
                ->comment('ID вида деятельности')
                ->constrained('activities')
                ->cascadeOnDelete();

            $table->primary(['organization_id', 'activity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_activity');
    }
};
