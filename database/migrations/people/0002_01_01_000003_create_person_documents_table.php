<?php

use Database\Support\TableConfig;
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
      $table->uuid('id')->index();
      $table->uuid('person_id')->index();
      $table->uuid('document_type_id');
      $table->string('doc_id')->nullable();
      $table->timestamps();

      $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
      $table->foreign('document_type_id')->references('id')->on('person_document_types')->onDelete('cascade');
    });

    DB::unprepared(TableConfig::getFunctionConfigBeforeInsert($this->table));

    DB::unprepared(TableConfig::getFunctionConfigBeforeUpdate($this->table));
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists($this->table);
  }
};
