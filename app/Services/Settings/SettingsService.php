<?php

namespace App\Services\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected static string $cachePrefix = 'settings.';
    protected static string $currentLanguage = 'en';

    public function __construct()
    {
        self::$currentLanguage = Cache::get('CURRENT_LANGUAGE', env('CURRENT_LANGUAGE', 'en'));
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(self::$cachePrefix.$key, function () use ($key, $default) {
            return Setting::query()->where('key', $key)->value('value') ?? $default;
        });
    }

    public function set(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::$cachePrefix.$key);
    }

    /**
     * @return array<int, array{code: string, label: string}>
     */
    public function availableLocales(): array
    {
        $value = $this->get('locales.available', []);

        if (!is_array($value)) {
            return [];
        }

        // Normaliza entradas mínimas
        return collect($value)
            ->filter(fn ($l) => is_array($l) && !empty($l['code']))
            ->map(fn ($l) => ['code' => (string) $l['code'], 'label' => (string) ($l['label'] ?? $l['code'])])
            ->values()
            ->all();
    }

    public function defaultLocale(): string
    {
        $default = (string) ($this->get('locales.default', self::$currentLanguage) ?? self::$currentLanguage);
        $codes = collect($this->availableLocales())->pluck('code')->all();

        return in_array($default, $codes, true) ? $default : ($codes[0] ?? self::$currentLanguage);
    }
}

