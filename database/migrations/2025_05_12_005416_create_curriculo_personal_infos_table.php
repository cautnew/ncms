<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('curriculo_personal_infos', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('version', '40')->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->date('dt_of_birth');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculo_personal_infos');
    }
};
