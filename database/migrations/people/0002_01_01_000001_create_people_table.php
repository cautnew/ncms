<?php

use Database\Support\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  public string $table = 'people';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->table, function (Blueprint $table) {
      $table->uuid('id')->index();
      $table->foreignUuid('user_id')->index();
      $table->string('name');
      $table->string('lastname')->nullable();
      $table->date('birthdate')->nullable();
      $table->timestamps();
    });

    Schema::create($this->table . '_his', function (Blueprint $table) {
      $table->uuid('id')->nullable();
      $table->uuid('user_id')->nullable();
      $table->string('name')->nullable();
      $table->string('lastname')->nullable();
      $table->date('birthdate')->nullable();
      $table->timestamps();
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
  }
};
