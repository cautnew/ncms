<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $connectionName = 'brutosdb.users';
    private string $table = 'users';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->down();

        $schema = Schema::connection($this->connectionName);
        $schema->create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_central_stock_admin')->default(false);
            $table->string('access_code')->nullable();
            $table->string('parent_id')->nullable();
            $table->timestamps();
        });

        $schema->create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        $schema->create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schema = Schema::connection($this->connectionName);
        $schema->dropIfExists($this->table);
        $schema->dropIfExists('password_reset_tokens');
        $schema->dropIfExists('sessions');
    }
};
