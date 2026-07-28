<?php

use App\Models\Pages\PageLayout;
use App\Models\User;
use App\Models\Websites\Website;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'page_layouts';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuidFor(Website::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('template_class_name');
            $table->foreignUuidFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'updated_by')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'deleted_by')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['website_id', 'slug']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->foreignUuidFor(PageLayout::class)->after('website_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
