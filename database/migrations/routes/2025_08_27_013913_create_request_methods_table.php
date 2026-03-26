<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Pages\Page;

return new class extends Migration {

    private string $table = 'request_methods';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('code')->unique()->index();
            $table->foreignUuid('created_by')
              ->nullable()
              ->constrained('users', 'id');
            $table->foreignUuid('updated_by')
              ->nullable()
              ->constrained('users', 'id');
            $table->foreignUuid('deleted_by')
              ->nullable()
              ->constrained('users', 'id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
