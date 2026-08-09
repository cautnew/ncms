<?php

declare(strict_types=1);

use App\Support\Database\UserstampSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('website_id')->constrained('websites')->cascadeOnDelete();
            $table->foreignUuid('layout_id')->nullable()->constrained('layouts')->cascadeOnDelete();
            $table->foreignUuid('page_version_id')->nullable()->constrained('page_versions')->cascadeOnDelete();
            $table->string('disk', 50)->default('public');
            $table->string('path', 2048);
            $table->string('filename');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size');
            $table->json('metadata')->nullable();
            UserstampSchema::columns($table);
            $table->timestamps();
            $table->softDeletes();

            $table->index('mime_type');

            $table->comment('Media library. Each asset belongs to exactly one owner: a layout or a page_version.');
        });

        // Enforced via triggers, not a CHECK constraint: MariaDB (error 1901) refuses a CHECK that
        // references a column governed by an ON DELETE CASCADE foreign key (layout_id, page_version_id).
        DB::unprepared(
            "CREATE TRIGGER trg_assets_single_owner_insert
                BEFORE INSERT ON assets
                FOR EACH ROW
                BEGIN
                    IF (NEW.layout_id IS NULL AND NEW.page_version_id IS NULL)
                        OR (NEW.layout_id IS NOT NULL AND NEW.page_version_id IS NOT NULL) THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'An asset must belong to exactly one of layout_id or page_version_id.';
                    END IF;
                END"
        );

        DB::unprepared(
            "CREATE TRIGGER trg_assets_single_owner_update
                BEFORE UPDATE ON assets
                FOR EACH ROW
                BEGIN
                    IF (NEW.layout_id IS NULL AND NEW.page_version_id IS NULL)
                        OR (NEW.layout_id IS NOT NULL AND NEW.page_version_id IS NOT NULL) THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'An asset must belong to exactly one of layout_id or page_version_id.';
                    END IF;
                END"
        );

        UserstampSchema::installTriggers('assets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserstampSchema::dropTriggers('assets');

        DB::unprepared('DROP TRIGGER IF EXISTS trg_assets_single_owner_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_assets_single_owner_update');

        Schema::dropIfExists('assets');
    }
};
