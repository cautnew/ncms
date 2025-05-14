<?php

use App\Models\Curriculo\CurriculoVersions;
use App\Models\NCMS\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'curriculos';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignIdFor(CurriculoVersions::class, 'current_version');
            $table->foreignIdFor(User::class, 'user_id');
            $table->string('name', '40');
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
