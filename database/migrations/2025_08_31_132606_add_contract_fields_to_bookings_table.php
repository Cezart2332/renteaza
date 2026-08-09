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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('contract_s3_key')->nullable()->after('status');
            $table->timestamp('owner_signed_at')->nullable()->after('contract_s3_key');
            $table->timestamp('client_signed_at')->nullable()->after('owner_signed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'contract_s3_key',
                'owner_signed_at',
                'client_signed_at',
            ]);
        });
    }
};
