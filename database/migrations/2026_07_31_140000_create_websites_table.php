<?php

declare(strict_types=1);

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
        Schema::create('websites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('domain');
            $table->string('subdomain')->default('');
            $table->string('locale', 10)->default('pt-BR');
            $table->string('timezone', 64)->nullable();
            $table->enum('status', ['active', 'suspended', 'archived'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['domain', 'subdomain']);
            $table->index('status');

            $table->comment('Root tenant entity for each managed site: domain, subdomain and locale.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
