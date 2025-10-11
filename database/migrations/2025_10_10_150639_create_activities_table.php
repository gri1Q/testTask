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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название деятельности')->index();
            $table->text('description')->nullable()->comment('Описание деятельности');
            $table->foreignId('parent_id')
                ->nullable()
                ->comment('ID родительской деятельности')
                ->constrained('activities')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('level')->default(1)->comment('Уровень вложенности');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
