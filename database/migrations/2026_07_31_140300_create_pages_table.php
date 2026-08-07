<?php

declare(strict_types=1);

use App\Support\Database\UserstampSchema;
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
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('website_id')->constrained('websites')->cascadeOnDelete();
            $table->foreignUuid('layout_id')->constrained('layouts')->restrictOnDelete();

            // Foreign key added in a later migration (add_published_version_foreign_to_pages_table),
            // once the page_versions table exists — pages and page_versions reference each other.
            $table->uuid('published_version_id')->nullable();

            $table->string('slug');
            $table->enum('status', ['active', 'archived'])->default('active');
            UserstampSchema::columns($table);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['website_id', 'slug']);
            $table->index(['website_id', 'status']);
            $table->index('published_version_id');

            $table->comment('Pages belonging to a website. published_version_id points to the currently live, immutable page_version.');
        });

        UserstampSchema::installTriggers('pages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserstampSchema::dropTriggers('pages');

        Schema::dropIfExists('pages');
    }
};
