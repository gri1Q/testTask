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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название организации');
            $table->text('description')->nullable()->comment('Описание организации');
            $table->string('email')->nullable()->comment('Контактный email');
            $table->unsignedBigInteger('building_id')->comment('ID здания, в котором находится организация');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID родительской организации');
            $table->timestamps();

            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->cascadeOnDelete();

            $table->foreign('parent_id')
                ->references('id')
                ->on('organizations')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
