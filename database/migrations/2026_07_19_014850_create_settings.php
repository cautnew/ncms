<?php

use App\Models\User;
use App\Models\Websites\Website;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'settings';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuidFor(Website::class)->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('data');
            $table->foreignUuidFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'updated_by')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'deleted_by')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['website_id', 'key']);
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
