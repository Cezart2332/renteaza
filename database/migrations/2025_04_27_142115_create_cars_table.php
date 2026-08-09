<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->enum('fuel_type', ['electric', 'hibrid']);
            $table->integer('autonomy_km');
            $table->enum('transmission', ['automat', 'manual']);
            $table->decimal('price_per_day', 8, 2);
            $table->string('cover_image');
            $table->json('gallery_images')->nullable();
            $table->string('location');
            $table->string('license_plate');
            $table->date('insurance_valid_until');
            $table->text('description')->nullable();
            $table->json('availability_calendar')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
