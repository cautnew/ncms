<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public string $table = 'person_documents';
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->table, function (Blueprint $table) {
      $table->uuid('id')->nullable()->index();
      $table->uuid('person_id')->index();
      $table->id('document_type_id');
      $table->string('doc_id')->nullable();
      $table->timestamps();

      $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
      $table->foreign('document_type_id')->references('id')->on('person_document_types')->onDelete('cascade');
    });

    DB::unprepared(implode("\n", [
      "CREATE TRIGGER tg_{$this->table}_before_insert BEFORE INSERT on `{$this->table}` FOR EACH ROW",
      "BEGIN",
      "SET new.id = IF (new.id is null, uuid(), new.id);",
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
