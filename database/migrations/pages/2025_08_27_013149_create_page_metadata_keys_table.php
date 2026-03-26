<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pages\Page;
use App\Models\Pages\Type;
use App\Models\User;

return new class extends Migration
{
    private string $table = 'page_metadata_keys';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table_construction = function (Blueprint $table, bool $isHistory = false) {
            ($isHistory) ? $table->uuid('id') : $table->uuid('id')->primary();

            $table->string('name');
            $table->string('key')->unique();
            $table->boolean('is_nullable')->default(false);
            $table->boolean('is_required')->default(false);

            if ($isHistory) {
                $table->uuid('created_by');
                $table->uuid('updated_by');
            } else {
                $table->foreignUuid('created_by')->constrained('users', 'id')->cascadeOnDelete();
                $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->cascadeOnDelete();
            }

            $table->timestamps();
        };

        Schema::create($this->table, function (Blueprint $table) use ($table_construction) {
            $table_construction($table);
        });

        Schema::create('his_' . $this->table, function (Blueprint $table) use ($table_construction) {
            $table_construction($table, true);
        });

        // Create triggers to copy inserted data from $table to his_$table
        $trigger_insert = <<<SQL
            CREATE TRIGGER trg_{$this->table}_insert
            AFTER INSERT ON {$this->table}
            FOR EACH ROW
            BEGIN
                INSERT INTO his_{$this->table} (
                    `id`,
                    `name`,
                    `key`,
                    `is_nullable`,
                    `is_required`,
                    `created_by`,
                    `updated_by`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    NEW.id,
                    NEW.name,
                    NEW.key,
                    NEW.is_nullable,
                    NEW.is_required,
                    NEW.created_by,
                    NEW.updated_by,
                    NEW.created_at,
                    NEW.updated_at
                );
            END;
        SQL;

        $trigger_update = <<<SQL
            CREATE TRIGGER trg_{$this->table}_update
            AFTER UPDATE ON {$this->table}
            FOR EACH ROW
            BEGIN
                INSERT INTO his_{$this->table} (
                    `id`,
                    `name`,
                    `key`,
                    `is_nullable`,
                    `is_required`,
                    `created_by`,
                    `updated_by`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    NEW.id,
                    NEW.name,
                    NEW.key,
                    NEW.is_nullable,
                    NEW.is_required,
                    NEW.created_by,
                    NEW.updated_by,
                    NEW.created_at,
                    NEW.updated_at
                );
            END;
        SQL;

        $trigger_delete = <<<SQL
            CREATE TRIGGER trg_{$this->table}_delete
            AFTER DELETE ON {$this->table}
            FOR EACH ROW
            BEGIN
                INSERT INTO his_{$this->table} (
                    `id`,
                    `name`,
                    `key`,
                    `is_nullable`,
                    `is_required`,
                    `created_by`,
                    `updated_by`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    OLD.id,
                    OLD.name,
                    OLD.key,
                    OLD.is_nullable,
                    OLD.is_required,
                    OLD.created_by,
                    OLD.updated_by,
                    OLD.created_at,
                    OLD.updated_at
                );
            END;
        SQL;

        DB::unprepared($trigger_insert);
        DB::unprepared($trigger_update);
        DB::unprepared($trigger_delete);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
