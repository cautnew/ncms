<?php

use Database\Support\TableConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public string $table = 'pages';
  public string $model = App\Models\Pages\Page::class;

  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create($this->table, function (Blueprint $table) {
      $table->uuid('id')->index();
      $table->string('name');
      $table->string('alias');
      $table->text('description')->nullable();
      $table->foreignUuid('user_id')->index();
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
