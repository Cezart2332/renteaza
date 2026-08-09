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
        Schema::table('bookings', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('payout_reference')->nullable()->after('payout_status'); // ex: Nr. OP / ref. externă
            $table->timestamp('paid_out_at')->nullable()->after('payout_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('payout_reference');
            $table->dropColumn('paid_out_at');
        });
    }
};
