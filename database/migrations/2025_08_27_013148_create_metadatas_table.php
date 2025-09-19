<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Pages\Page;

return new class extends Migration {
    private string $table = 'metadatas';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignIdFor(Page::class, 'page_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
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
