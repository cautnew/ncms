<?php

use App\Models\Pages\PageVersion;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'page_data';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuidFor(PageVersion::class)->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('data');
            $table->foreignUuidFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'updated_by')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'deleted_by')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['page_version_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_before_insert_pages');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_before_update_pages');
        Schema::dropIfExists($this->table);
    }
};
