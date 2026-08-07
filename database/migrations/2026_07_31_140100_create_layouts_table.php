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
        Schema::create('layouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('website_id')->constrained('websites')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->json('schema')->nullable();
            $table->enum('status', ['draft', 'active', 'archived'])->default('active');
            $table->boolean('is_default')->default(false);
            UserstampSchema::columns($table);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['website_id', 'slug']);
            $table->index(['website_id', 'is_default']);
            $table->index(['website_id', 'status']);

            $table->comment('Reusable page layout templates, scoped per website.');
        });

        UserstampSchema::installTriggers('layouts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserstampSchema::dropTriggers('layouts');

        Schema::dropIfExists('layouts');
    }
};
