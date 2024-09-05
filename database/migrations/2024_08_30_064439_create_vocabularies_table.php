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
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('definition');
            $table->text('synonyms');
            $table->text('antonyms');
            $table->enum('type', config('enums.vocabulary_type'))->nullable()->default(null);
            $table->text('example')->nullable()->default(null);
            $table->boolean('is_revised')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};