<?php

declare(strict_types=1);

use App\Support\Database\UserstampSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
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
        Schema::create('pieces', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('page_version_id')->constrained('page_versions')->cascadeOnDelete();
            $table->foreignUuid('parent_piece_id')->nullable()->constrained('pieces')->cascadeOnDelete();
            $table->foreignUuid('asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->string('type', 50);
            $table->string('slot', 50)->nullable();
            $table->string('region', 50)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->json('content')->default(new Expression('(JSON_OBJECT())'));
            $table->json('settings')->default(new Expression('(JSON_OBJECT())'));
            UserstampSchema::columns($table);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['page_version_id', 'parent_piece_id', 'position'], 'pieces_tree_order_index');

            $table->comment('Hierarchical content components (tags/blocks) of a page_version. Tree built via parent_piece_id.');
        });

        // Enforced via triggers, not a CHECK constraint: MariaDB (error 1901) refuses a CHECK that
        // references a column governed by an ON DELETE CASCADE foreign key (parent_piece_id).
        DB::unprepared(
            "CREATE TRIGGER trg_pieces_no_self_parent_insert
                BEFORE INSERT ON pieces
                FOR EACH ROW
                BEGIN
                    IF NEW.parent_piece_id IS NOT NULL AND NEW.parent_piece_id = NEW.id THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'A piece cannot be its own parent.';
                    END IF;
                END"
        );

        DB::unprepared(
            "CREATE TRIGGER trg_pieces_no_self_parent_update
                BEFORE UPDATE ON pieces
                FOR EACH ROW
                BEGIN
                    IF NEW.parent_piece_id IS NOT NULL AND NEW.parent_piece_id = NEW.id THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'A piece cannot be its own parent.';
                    END IF;
                END"
        );

        UserstampSchema::installTriggers('pieces');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserstampSchema::dropTriggers('pieces');

        DB::unprepared('DROP TRIGGER IF EXISTS trg_pieces_no_self_parent_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_pieces_no_self_parent_update');

        Schema::dropIfExists('pieces');
    }
};
