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
        Schema::table('bookings', function ($t) {
            $t->string('stripe_transfer_id')->nullable()->after('stripe_payment_intent');
            $t->integer('platform_fee_amount')->nullable()->after('stripe_transfer_id');
            $t->string('payout_status')->default('none')->after('platform_fee_amount'); // none|manual_required
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function ($t) {
            $t->dropColumn('stripe_transfer_id');
            $t->dropColumn('platform_fee_amount');
            $t->dropColumn('payout_status');
        });
    }
};
