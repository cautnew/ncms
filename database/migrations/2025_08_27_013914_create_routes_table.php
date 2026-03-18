<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Pages\Page;
use App\Models\Core\RequestMethods\RequestMethod;
use App\Models\Core\ResponseTypes\ResponseType;

return new class extends Migration {

    private string $table = 'routes';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->foreignIdFor(RequestMethod::class, 'request_method_id')->default(1)->comment('The HTTP method for the route. The order is GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD and TRACE.');
            $table->string('route')->unique()->index();
            $table->foreignIdFor(Page::class, 'page_id')->index()->cascadeOnDelete();
            $table->string('controller_class')->nullable();
            $table->boolean('is_redirect')->default(false);
            $table->string('redirect_to')->nullable()->comment('The URL to redirect to if is_redirect is true');
            $table->foreignIdFor(ResponseType::class, 'redirect_type_id')->nullable()->comment('The type of redirect (e.g., 301, 302)');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_authenticated_only')->default(false);
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
