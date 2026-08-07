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
        Schema::create('website_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('website_id')->constrained('websites')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['owner', 'admin', 'editor', 'qa', 'viewer']);
            $table->foreignUuid('invited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            UserstampSchema::columns($table);
            $table->timestamps();
            $table->softDeletes();

            // Not a plain unique index: a removed member (soft-deleted row) must not
            // block re-inviting the same user later. Uniqueness among *live* rows only
            // is enforced below via triggers instead.
            $table->index(['website_id', 'user_id']);

            $table->comment('Membership pivot linking users to websites with a role (owner, admin, editor, qa, viewer).');
        });

        DB::unprepared(
            "CREATE TRIGGER trg_website_users_unique_live_insert
                BEFORE INSERT ON website_users
                FOR EACH ROW
                BEGIN
                    IF NEW.deleted_at IS NULL AND EXISTS (
                        SELECT 1 FROM website_users
                        WHERE website_id = NEW.website_id
                            AND user_id = NEW.user_id
                            AND deleted_at IS NULL
                    ) THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'This user already has an active membership on this website.';
                    END IF;
                END"
        );

        DB::unprepared(
            "CREATE TRIGGER trg_website_users_unique_live_update
                BEFORE UPDATE ON website_users
                FOR EACH ROW
                BEGIN
                    IF NEW.deleted_at IS NULL AND EXISTS (
                        SELECT 1 FROM website_users
                        WHERE website_id = NEW.website_id
                            AND user_id = NEW.user_id
                            AND deleted_at IS NULL
                            AND id != NEW.id
                    ) THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'This user already has an active membership on this website.';
                    END IF;
                END"
        );

        UserstampSchema::installTriggers('website_users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserstampSchema::dropTriggers('website_users');

        DB::unprepared('DROP TRIGGER IF EXISTS trg_website_users_unique_live_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_website_users_unique_live_update');

        Schema::dropIfExists('website_users');
    }
};
