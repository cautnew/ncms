<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $table = "taxonomy_terms";

    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('taxonomy_id')->constrained('taxonomies')->cascadeOnDelete();
            $table->uuid('parent_id')->nullable();
            $table->string('slug');
            $table->integer('rank')->default(0); // display order within taxonomy
            $table->foreignUuid('created_by')->constrained('users', 'id');
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id');
            $table->foreignUuid('deleted_by')->nullable()->constrained('users', 'id');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['taxonomy_id', 'slug']);

            // Self-referencing foreign key for nested terms (like Drupal vocabulary hierarchy)
            $table->foreign('parent_id')->references('id')->on('taxonomy_terms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
