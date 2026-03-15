<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pages\Type;
use App\Models\User;

return new class extends Migration {

    private string $table = 'pages';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->foreignIdFor(Type::class, 'type_id')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'created_by')->cascadeOnDelete();
            $table->timestamps();
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
