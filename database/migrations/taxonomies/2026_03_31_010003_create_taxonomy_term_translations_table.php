<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $table = "taxonomy_term_translations";

    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('taxonomy_term_id')->constrained('taxonomy_terms')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignUuid('created_by')->constrained('users', 'id');
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id');
            $table->foreignUuid('deleted_by')->nullable()->constrained('users', 'id');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['taxonomy_term_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxonomy_term_translations');
    }
};
