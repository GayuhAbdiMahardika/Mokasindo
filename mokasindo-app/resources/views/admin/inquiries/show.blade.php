@extends('admin.layout')

@section('title', 'View Inquiry')
@section('page-title', 'Inquiry Details')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $inquiry->subject }}</h3>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span><i class="fas fa-user mr-1"></i>{{ $inquiry->name }}</span>
                    <span><i class="fas fa-envelope mr-1"></i>{{ $inquiry->email }}</span>
                    <span><i class="fas fa-calendar mr-1"></i>{{ $inquiry->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
            <div>
                @if($inquiry->status == 'new')
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">New</span>
                @elseif($inquiry->status == 'read')
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Read</span>
                @elseif($inquiry->status == 'replied')
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Replied</span>
                @else
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Spam</span>
                @endif
            </div>
        </div>

        <!-- Message -->
        <div class="border-t border-b py-4 mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Customer Message:</h4>
            <p class="text-gray-700 whitespace-pre-line">{{ $inquiry->message }}</p>
        </div>

        <!-- Reply Section -->
        @if($inquiry->admin_reply)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-semibold text-green-900 mb-2">
                    <i class="fas fa-check-circle mr-1"></i>Your Reply ({{ $inquiry->replied_at->format('d M Y, H:i') }}):
                </h4>
                <p class="text-green-800 whitespace-pre-line">{{ $inquiry->admin_reply }}</p>
            </div>
        @endif

        @if($inquiry->status != 'spam' && $inquiry->status != 'replied')
            <form method="POST" action="{{ route('admin.inquiries.reply', $inquiry) }}" class="mb-6">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-2">Reply to Customer:</label>
                <textarea name="admin_reply" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Type your reply here..." required>{{ old('admin_reply') }}</textarea>
                <div class="mt-4 flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        <i class="fas fa-reply mr-2"></i>Send Reply
                    </button>
                </div>
            </form>
        @endif

        <!-- Actions -->
        <div class="flex gap-4 pt-4 border-t">
            <a href="{{ route('admin.inquiries.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-md">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
            
            @if($inquiry->status != 'spam')
                <form method="POST" action="{{ route('admin.inquiries.spam', $inquiry) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-md" onclick="return confirm('Mark this as spam?')">
                        <i class="fas fa-ban mr-2"></i>Mark as Spam
                    </button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" class="inline ml-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md" onclick="return confirm('Delete this inquiry permanently?')">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
