<?php

namespace App\Http\Controllers\Settings\Templates;

use App\Http\Controllers\Controller;
use App\Services\Templates\TemplatesManager;
use Inertia\Inertia;
use Inertia\Response;

class TemplateController extends Controller
{
    /**
     * Show the user's template settings page.
     */
    public function index(): Response
    {
        return Inertia::render('settings/template/template', TemplatesManager::getJsonObject());
    }
}
