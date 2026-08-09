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
        Schema::rename('checkin_submissions', 'inspection_submissions');

        Schema::table('inspection_submissions', function (Blueprint $table) {
            $table->string('type')->default('checkin')->after('booking_id'); // 'checkin' | 'checkout'
            // index compus util la listări/rapoarte:
            $table->index(['booking_id', 'type', 'status'], 'inspections_triplet_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_submissions', function (Blueprint $table) {
            $table->dropIndex('inspections_triplet_idx');
            $table->dropColumn('type');
        });

        Schema::rename('inspection_submissions', 'checkin_submissions');
    }
};
