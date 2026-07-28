<?php

namespace Database\Seeders;

use App\Models\Pages\Page;
use App\Models\Pages\PageData;
use App\Models\Pages\PageLayout;
use App\Models\Pages\PageVersion;
use App\Models\User;
use App\Models\Websites\Website;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private function seedingPages(Page $page)
    {
        PageVersion::factory(5)->create([
            'page_id' => $page->id,
        ]);

        $page->versions->each(function (PageVersion $pageVersion) {
            PageData::factory(10)->create([
                'page_version_id' => $pageVersion->id,
            ]);
        });

        $this->getRandomPageVersion($page)->update(['is_current' => true]);

        $randomPageVersion = $this->getRandomPageVersion($page);
        if (! $randomPageVersion->is_current) {
            $randomPageVersion->update(['is_current_editing' => true]);
        }
    }

    private function getRandomPageVersion(Page $page): PageVersion
    {
        return $page->versions()->inRandomOrder()->first();
    }

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
        PageLayout::factory(8)->create();
        Page::factory(10)->create();

        Page::all()->each(fn (Page $page) => $this->seedingPages($page)
        );
    }
}
