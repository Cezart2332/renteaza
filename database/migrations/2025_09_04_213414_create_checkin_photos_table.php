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
        Schema::create('checkin_photos', function (Blueprint $table) {
            $table->id();

            // FK către submission (BIGINT din tabela anterioară)
            $table->foreignId('submission_id')
                ->constrained('checkin_submissions')
                ->cascadeOnDelete();

            // Poziție 1..4 (asigurăm unicitate per submission)
            $table->unsignedTinyInteger('position'); // 1,2,3,4
            $table->unique(['submission_id', 'position']);

            // Metadate fișier
            $table->string('path', 2048);           // ex: s3 path
            $table->string('original_name')->nullable();
            $table->string('mime', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable();   // bytes
            $table->unsignedInteger('width')->nullable();     // px (opțional)
            $table->unsignedInteger('height')->nullable();    // px (opțional)

            $table->timestamps();

            $table->index('submission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_photos');
    }
};
