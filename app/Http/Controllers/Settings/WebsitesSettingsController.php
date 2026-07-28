<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Websites\Website;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WebsitesSettingsController extends Controller
{
    public function index()
    {
        $websites = Website::all();

        return Inertia::render('settings/websites/index', ['websites_list' => $websites]);
    }

    public function create()
    {
        return Inertia::render('settings/websites/create');
    }

    public function update(Request $request): RedirectResponse
    {
        return to_route('settings.websites.edit');
    }
}
