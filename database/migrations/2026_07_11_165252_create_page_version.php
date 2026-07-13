<?php

use App\Models\Pages\Page;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = "page_versions";

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('locale')->nullable();
            $table->string('name');
            $table->foreignUuidFor(Page::class)->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_current_editing')->default(false);
            $table->boolean('is_current')->default(false);
            $table->foreignUuidFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'updated_by')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'deleted_by')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
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
