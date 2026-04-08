<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInterfaceProperties extends Middleware
{
    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            'interfaceProperties' => [
                'appNavItemsMain' => [
                    ['name' => 'Dashboard', 'href' => route('dashboard', absolute: false), 'iconName' => 'LayoutGrid'],
                    ['name' => 'Pages', 'href' => route('pages', absolute: false), 'iconName' => 'Layers'],
                    ['name' => 'Media', 'href' => route('media', absolute: false), 'iconName' => 'CassetteTape'],
                    ['name' => 'Taxonomy', 'href' => route('taxonomy.index', absolute: false), 'iconName' => 'BookCopy'],
                    ['name' => 'Routes', 'href' => route('routes', absolute: false), 'iconName' => 'RouteIcon'],
                ],
            ],
        ];
    }
}
