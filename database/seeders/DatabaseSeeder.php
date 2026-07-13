<?php

namespace Database\Seeders;

use App\Models\Pages\Page;
use App\Models\Pages\PageData;
use App\Models\Pages\PageVersion;
use App\Models\User;
use App\Models\Websites\Website;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@email.com',
            'is_admin' => true,
        ]);

        User::factory(10)->create();

        Website::factory(5)->create();
        Page::factory(10)->create();

        Page::all()->each(function (Page $page) {
            PageVersion::factory(5)->create([
                'page_id' => $page->id,
            ]);

            $page->versions->each(function (PageVersion $pageVersion) {
                PageData::factory(10)->create([
                    'page_version_id' => $pageVersion->id,
                ]);
            });

            $randomPageVersionCurrent = $page->versions()->inRandomOrder()->first();
            $randomPageVersionCurrentEditing = $page->versions()->inRandomOrder()->first();

            $randomPageVersionCurrent->update(['is_current' => true,]);
            $randomPageVersionCurrentEditing->update(['is_current_editing' => true,]);
        });
    }
}
