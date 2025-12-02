<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PageRevision;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('title')->paginate(15);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $this->prepareSlug($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'meta_description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page berhasil ditambahkan!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $this->prepareSlug($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        // Save revision before updating
        PageRevision::create([
            'page_id' => $page->id,
            'user_id' => Auth::id(),
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
            'meta_description' => $page->meta_description,
            'is_published' => $page->is_published,
        ]);

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page berhasil diupdate!');
    }

    // Revisions list
    public function revisions(Page $page)
    {
        $revisions = PageRevision::where('page_id', $page->id)->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pages.revisions', compact('page', 'revisions'));
    }

    // Revert to a revision
    public function revertRevision(Page $page, PageRevision $revision)
    {
        // Save current as revision
        PageRevision::create([
            'page_id' => $page->id,
            'user_id' => Auth::id(),
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
            'meta_description' => $page->meta_description,
            'is_published' => $page->is_published,
        ]);

        // Apply revision
        $page->update([
            'title' => $revision->title,
            'slug' => $revision->slug,
            'content' => $revision->content,
            'meta_description' => $revision->meta_description,
            'is_published' => $revision->is_published,
        ]);

        return redirect()->route('admin.pages.revisions', $page)->with('success', 'Page reverted to selected revision');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page berhasil dihapus!');
    }

    public function togglePublish(Page $page)
    {
        $page->update(['is_published' => !$page->is_published]);
        
        $status = $page->is_published ? 'dipublish' : 'di-unpublish';
        return redirect()->route('admin.pages.index')->with('success', "Page berhasil {$status}!");
    }

    private function prepareSlug(Request $request): void
        {
            $sourceValue = $request->filled('slug') ? $request->input('slug') : $request->input('title');
            $slug = Str::slug($sourceValue ?? '');

            if (blank($slug)) {
                $slug = Str::slug('page-' . now()->timestamp);
            }

            $request->merge(['slug' => $slug]);
        }
}
