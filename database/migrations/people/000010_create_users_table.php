<?php

use Database\Support\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public string $table = 'users';
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->uuid('id')->index();
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->rememberToken();
      $table->timestamps();
    });

    Schema::create($this->table . '_his', function (Blueprint $table) {
      $table->uuid('id')->nullable();
      $table->string('email')->nullable();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password')->nullable();
      $table->rememberToken();
      $table->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('email')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignUuid('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
    });

    DB::unprepared(TableConfig::getFunctionConfigBeforeInsert($this->table));

    DB::unprepared(implode("\n", [
      "CREATE TRIGGER tg_{$this->table}_after_insert AFTER INSERT on `{$this->table}` FOR EACH ROW",
      "BEGIN",
      "INSERT INTO `{$this->table}_his` SELECT a.* FROM `{$this->table}` a WHERE a.id=new.id;",
      "END;"
    ]));

    DB::unprepared(TableConfig::getFunctionConfigBeforeUpdate($this->table));

    DB::unprepared(implode("\n", [
      "CREATE TRIGGER tg_{$this->table}_after_update AFTER UPDATE on `{$this->table}` FOR EACH ROW",
      "BEGIN",
      "INSERT INTO `{$this->table}_his` SELECT a.* FROM `{$this->table}` a WHERE a.id=new.id;",
      "END;"
    ]));
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists($this->table);
    Schema::dropIfExists($this->table . '_his');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('sessions');
  }
};
