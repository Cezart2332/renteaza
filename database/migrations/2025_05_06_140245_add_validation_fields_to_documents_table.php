<?php

use App\Enums\DocumentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->enum('status', DocumentStatus::values())
                ->default(DocumentStatus::PENDING->value)
                ->after('path');

            $table->timestamp('verified_at')
                ->nullable()
                ->after('status');

            $table->text('admin_comment')
                ->nullable()
                ->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['status', 'verified_at', 'admin_comment']);
        });
    }
};
