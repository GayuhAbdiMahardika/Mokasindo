<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageRevision;
use Illuminate\Support\Str;

class PageRevisionSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::first();
        if (!$page) {
            return;
        }

        PageRevision::updateOrCreate(
            ['page_id' => $page->id, 'slug' => $page->slug . '-rev-1'],
            [
                'user_id' => null,
                'title' => $page->title . ' (Revisi Awal)',
                'content' => $page->content,
                'meta_description' => Str::limit(strip_tags($page->content), 160),
                'is_published' => false,
            ]
        );
    }
}
