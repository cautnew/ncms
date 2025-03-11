<?php

use Database\Support\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public string $table = 'person_document_types';

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->table, function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('name');
      $table->string('description')->nullable();
      $table->string('code')->index();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
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
