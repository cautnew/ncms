<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pages\Type;
use App\Models\User;

return new class extends Migration {

    private string $table = 'pages';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->foreignUuid('type_id')->constrained('page_types', 'id')->cascadeOnDelete();
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->cascadeOnDelete();
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
