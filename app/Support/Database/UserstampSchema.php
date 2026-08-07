<?php

namespace App\Support\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

/**
 * Shared columns/triggers for the "who created/updated/deleted this row, and
 * when" convention applied to every domain table. Centralized here because
 * the same ~20 lines would otherwise be duplicated verbatim across 7
 * migrations — mirrors this project's existing pattern of enforcing
 * DB-level invariants (that CHECK constraints can't express under MariaDB)
 * via triggers, as already done for assets/pieces.
 *
 * created_by/updated_by are NOT NULL: a write with no identifiable actor is
 * rejected by the database itself (defense in depth — the application layer
 * already refuses it first, see App\Models\Concerns\HasUserstamps). Dates
 * are never enforced this way: if the application omits created_at/
 * updated_at, the trigger silently fills them in with NOW() instead of
 * failing the write.
 */
final class UserstampSchema
{
    /**
     * Adds updated_by/deleted_by, and created_by unless the table already
     * has its own actor column serving that role (page_versions.created_by,
     * assets.uploaded_by) that HasUserstamps has been told to reuse instead.
     */
    public static function columns(Blueprint $table, bool $includeCreatedBy = true): void
    {
        if ($includeCreatedBy) {
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
        }

        $table->foreignUuid('updated_by')->constrained('users')->restrictOnDelete();
        $table->foreignUuid('deleted_by')->nullable()->constrained('users')->nullOnDelete();
    }

    /**
     * Installs the BEFORE INSERT / BEFORE UPDATE triggers that fill in
     * created_at/updated_at when the application didn't supply them, and
     * keep deleted_at/deleted_by consistent with each other for any write
     * that bypasses Eloquent (raw SQL, other tooling).
     */
    public static function installTriggers(string $table): void
    {
        DB::unprepared(
            "CREATE TRIGGER trg_{$table}_userstamp_insert
                BEFORE INSERT ON {$table}
                FOR EACH ROW
                BEGIN
                    IF NEW.created_at IS NULL THEN
                        SET NEW.created_at = NOW();
                    END IF;
                    IF NEW.updated_at IS NULL THEN
                        SET NEW.updated_at = NEW.created_at;
                    END IF;
                END"
        );

        DB::unprepared(
            "CREATE TRIGGER trg_{$table}_userstamp_update
                BEFORE UPDATE ON {$table}
                FOR EACH ROW
                BEGIN
                    IF NEW.updated_at IS NULL OR NEW.updated_at = OLD.updated_at THEN
                        SET NEW.updated_at = NOW();
                    END IF;

                    IF NEW.deleted_by IS NOT NULL AND OLD.deleted_by IS NULL AND NEW.deleted_at IS NULL THEN
                        SET NEW.deleted_at = NOW();
                    END IF;

                    IF NEW.deleted_at IS NULL AND OLD.deleted_at IS NOT NULL THEN
                        SET NEW.deleted_by = NULL;
                    END IF;
                END"
        );
    }

    public static function dropTriggers(string $table): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_{$table}_userstamp_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_{$table}_userstamp_update");
    }
}
