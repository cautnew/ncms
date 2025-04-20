<?php

use App\Models\NCMS\User;
use App\Models\NCMS\Content;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'routes';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->enum('method', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD', 'TRACE'])->default('GET')->comment('The HTTP method for the route');
            $table->string('route')->unique()->comment('The route/path URI');
            $table->foreignIdFor(Content::class)->nullable()->onDelete('cascade')->comment('The content associated with this route');
            $table->string('controller_class')->nullable();
            $table->string('method_get')->nullable();
            $table->string('method_post')->nullable();
            $table->string('method_put')->nullable();
            $table->string('method_delete')->nullable();
            $table->string('method_patch')->nullable();
            $table->string('method_options')->nullable();
            $table->boolean('is_redirect')->default(false);
            $table->string('redirect_to')->nullable()->comment('The URL to redirect to if is_redirect is true');
            $table->string('redirect_type')->nullable()->comment('The type of redirect (e.g., 301, 302)');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_authenticated_only')->default(false);
            $table->foreignIdFor(User::class)->onDelete('cascade');
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
