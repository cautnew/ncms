<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pages\Page;
use App\Models\Pages\Type;
use App\Models\User;

return new class extends Migration
{
    private string $table = 'page_metadata_values';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('metadata_id')->constrained('page_metadata', 'id')->cascadeOnDelete();
            $table->foreignUuid('metadata_key_id')->constrained('page_metadata_keys', 'id')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->boolean('is_null')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignUuid('created_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->cascadeOnDelete();
            $table->timestamps();
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
