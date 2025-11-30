@extends('admin.layout')

@section('title', 'Page Revisions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Revisions for: {{ $page->title }}</h1>
        <a href="{{ route('admin.pages.index') }}" class="text-blue-600">Back to pages</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b">
                    <th class="py-2">#</th>
                    <th class="py-2">By</th>
                    <th class="py-2">Created At</th>
                    <th class="py-2">Excerpt</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revisions as $rev)
                <tr class="border-b">
                    <td class="py-3">{{ $loop->iteration + ($revisions->currentPage()-1)*$revisions->perPage() }}</td>
                    <td class="py-3">{{ $rev->user->name ?? 'System' }}</td>
                    <td class="py-3">{{ $rev->created_at->format('d M Y H:i') }}</td>
                    <td class="py-3 text-sm text-gray-600">{!! Illuminate\Support\Str::limit(strip_tags($rev->content), 150) !!}</td>
                    <td class="py-3">
                        <form method="POST" action="{{ route('admin.pages.revisions.revert', [$page, $rev]) }}" onsubmit="return confirm('Revert to this revision?')">@csrf
                            <button class="px-3 py-1 bg-yellow-500 text-white rounded">Revert</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $revisions->links() }}</div>
    </div>
</div>

@endsection
