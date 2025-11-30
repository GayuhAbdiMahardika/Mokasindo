@extends('admin.layout')

@section('title', 'Users')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Users</h1>
            <p class="text-sm text-gray-500">Manage registered users and roles</p>
        </div>
        <div class="flex items-center space-x-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email" class="px-3 py-2 border rounded-l-md">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-md"> <i class="fas fa-search mr-2"></i>Search</button>
            </form>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                <i class="fas fa-user-plus mr-2"></i>
                <span>Create Admin</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                        <th class="px-6 py-3 w-16">No</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3 w-32">Role</th>
                        <th class="px-6 py-3 w-40">Joined</th>
                        <th class="px-6 py-3 w-40">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $users->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">{{ strtoupper(substr($user->name,0,1)) }}</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->phone ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : ($user->role === 'owner' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($user->role ?? 'user') }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded mr-2 hover:bg-blue-100">
                                <i class="fas fa-edit mr-2"></i>
                                <span>Edit</span>
                            </a>

                            @if(auth()->user() && auth()->user()->role === 'owner')
                                <button type="button" class="btn-open-delete-modal inline-flex items-center px-3 py-1 bg-red-50 text-red-700 rounded hover:bg-red-100" data-action="{{ route('admin.users.destroy', $user) }}" data-name="{{ $user->name }}">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    <span>Delete</span>
                                </button>
                            @else
                                <button class="inline-flex items-center px-3 py-1 bg-gray-50 text-gray-400 rounded cursor-not-allowed" title="Only owner can delete users">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    <span>Delete</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Confirm Delete</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-700 mb-4">Are you sure you want to delete <span id="delete-user-name" class="font-medium"></span> ? This action cannot be undone.</p>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="delete-cancel" class="px-4 py-2 bg-gray-100 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const modal = document.getElementById('delete-modal');
            const deleteForm = document.getElementById('delete-form');
            const deleteUserName = document.getElementById('delete-user-name');
            const cancelBtn = document.getElementById('delete-cancel');

            function openModal(action, name) {
                deleteForm.action = action;
                deleteUserName.textContent = name;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.querySelectorAll('.btn-open-delete-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    const action = this.getAttribute('data-action');
                    const name = this.getAttribute('data-name');
                    openModal(action, name);
                });
            });

            cancelBtn.addEventListener('click', function () {
                closeModal();
            });

            // close modal when clicking outside modal content
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // close on Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        })();
    </script>
    @endpush
</div>
@endsection
