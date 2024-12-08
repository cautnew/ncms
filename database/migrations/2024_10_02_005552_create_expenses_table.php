<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * A table to store expenses (money spent).
   */
  public string $table = 'expenses';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->table, function (Blueprint $table) {
      $table->uuid('id')->index();
      $table->uuid('fund_id')->nullable();
      $table->date('effective_date')->nullable();
      $table->date('due_date')->nullable();
      $table->text('description')->nullable();
      $table->decimal('amount', 10, 2)->default(0.0);
      $table->timestamps();

      $table->foreign('fund_id')->references('id')->on('funds')->onDelete('cascade');
    });

    DB::unprepared(implode("\n", [
      "CREATE TRIGGER tg_{$this->table}_before_insert BEFORE INSERT on `{$this->table}` FOR EACH ROW",
      "BEGIN",
      "SET new.created_at = now();",
      "SET new.updated_at = null;",
      "END;"
    ]));

    DB::unprepared(implode("\n", [
      "CREATE TRIGGER tg_{$this->table}_before_update BEFORE UPDATE on `{$this->table}` FOR EACH ROW",
      "BEGIN",
      "SET new.id = old.id;",
      "SET new.created_at = old.created_at;",
      "SET new.updated_at = now();",
      "END;"
    ]));
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists($this->table);
  }
};
