<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'websites';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('domain')->unique()->comment('The full domain name, e.g., www.example.com');
            $table->string('subdomain')->comment('The subdomain part of the domain, e.g., www');
            $table->string('sld')->comment('The second-level domain, e.g., example in www.exemple.com');
            $table->string('tld')->comment('The top-level domain, e.g., com in www.example.com');
            $table->string('cctld')->comment('The country-code top-level domain, e.g., uk in www.example.co.uk');
            $table->boolean('is_active')->default(true);
            $table->foreignUuidFor(User::class, 'created_by')->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'updated_by')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuidFor(User::class, 'deleted_by')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_before_insert_websites');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_before_update_websites');
        Schema::dropIfExists($this->table);
    }
};
