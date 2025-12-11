<?php

namespace App\Http\Controllers\Settings\Templates;

use App\Http\Controllers\Controller;
use App\Services\Templates\TemplatesManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TemplateAdjustmentsController extends Controller
{
    /**
     * Show the user's template settings page.
     */
    public function index(): Response
    {
        return Inertia::render('settings/template/adjustments', TemplatesManager::getJsonObject());
    }

    public function update(Request $request): RedirectResponse
    {
        $jsonObject = TemplatesManager::getJsonObject();
        $jsonObject = array_merge($jsonObject, $request->all());
        TemplatesManager::saveIntoJsonObject($jsonObject);

        return to_route('settings.template.adjustments');
    }
}
