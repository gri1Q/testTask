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
        Schema::create('organization_activity', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->comment('ID организации');
            $table->unsignedBigInteger('activity_id')->comment('ID вида деятельности');

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->cascadeOnDelete();

            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
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
