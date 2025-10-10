<?php

use App\Models\Users\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration {
    private string $table = 'people';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('name');
            $table->string('middle_name')->nullable();
            $table->string('lastname')->nullable();
            $table->date('birthdate')->nullable();
            $table->foreignIdFor(Gender::class, 'gender_id')->constrained();
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
