<?php

declare(strict_types=1);

use App\Support\Database\UserstampSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignUuid('layout_id')->constrained('layouts')->restrictOnDelete();
            $table->foreignUuid('cloned_from_id')->nullable()->constrained('page_versions')->nullOnDelete();
            $table->unsignedInteger('version_number');
            $table->enum('status', ['draft', 'under_review', 'approved', 'rejected', 'published', 'archived'])->default('draft');
            $table->json('layout_snapshot');
            $table->json('seo_snapshot')->nullable();
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->foreignUuid('published_by')->nullable()->constrained('users')->nullOnDelete();
            UserstampSchema::columns($table, includeCreatedBy: false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['page_id', 'version_number']);
            $table->index(['page_id', 'status']);

            $table->comment('Copy-on-write versions of a page. Published versions are immutable (enforced at the application layer).');
        });

        // Only version_number is checked at the database level: MariaDB (error 1901) refuses a CHECK
        // constraint that references a column governed by a non-RESTRICT foreign key action (published_by
        // uses ON DELETE SET NULL), since a cascading update could violate it outside of a normal write.
        // The "published requires published_at/published_by" invariant is therefore enforced by
        // PageVersionService in the application layer.
        DB::statement(
            'ALTER TABLE page_versions ADD CONSTRAINT chk_page_versions_version_number CHECK (version_number > 0)'
        );

        UserstampSchema::installTriggers('page_versions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserstampSchema::dropTriggers('page_versions');

        Schema::dropIfExists('page_versions');
    }
};
