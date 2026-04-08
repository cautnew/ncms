<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\Settings\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LocalesController extends Controller
{
    public function __construct(protected SettingsService $settings) {}

    public function edit(): Response
    {
        return Inertia::render('settings/locales', [
            'available' => $this->settings->availableLocales(),
            'default' => $this->settings->defaultLocale(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'available' => 'required|array|min:1',
            'available.*.code' => 'required|string|max:10',
            'available.*.label' => 'nullable|string|max:50',
            'default' => 'required|string|max:10',
        ]);

        $codes = collect($data['available'])->pluck('code')->unique()->values();
        if (!$codes->contains($data['default'])) {
            return back()->withErrors(['default' => 'The default language must be included in the available languages list.']);
        }

        $this->settings->set('locales.available', $data['available']);
        $this->settings->set('locales.default', $data['default']);

        return to_route('settings.locales.edit');
    }
}

