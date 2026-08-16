<?php

declare(strict_types=1);

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
        Schema::create('page_version_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('page_version_id')->constrained('page_versions')->cascadeOnDelete();
            $table->enum('decision', ['approved', 'rejected']);
            $table->foreignUuid('qa_user_id')->constrained('users')->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['page_version_id', 'created_at']);

            $table->comment('Append-only QA review history for a page version — one row per approve/reject decision. Never updated or deleted.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_version_reviews');
    }
};
