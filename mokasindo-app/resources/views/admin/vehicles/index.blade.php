@extends('admin.layout')

@section('title', 'Manage Vehicles')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Vehicles / Listings</h1>
        <form method="GET" action="{{ route('admin.vehicles.index') }}" class="flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search brand, model, plate" class="border rounded px-3 py-2" />
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All Status</option>
                <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                <option value="sold" {{ request('status')=='sold'?'selected':'' }}>Sold</option>
            </select>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
        </form>
    </div>

    <div class="flex items-center space-x-3 mb-4">
        <select name="action" id="bulkAction" class="border rounded px-3 py-2">
            <option value="">Bulk action</option>
            <option value="approve">Approve</option>
            <option value="set_featured">Set Featured</option>
            <option value="unset_featured">Unset Featured</option>
        </select>

        <select name="schedule_id" id="bulkSchedule" class="border rounded px-3 py-2">
            <option value="">Assign schedule (optional)</option>
            @foreach($schedules as $s)
            <option value="{{ $s->id }}">{{ $s->title }} — {{ $s->start_date->format('d M') }}</option>
            @endforeach
        </select>

        <button type="button" id="bulkApplyBtn" class="px-4 py-2 bg-indigo-600 text-white rounded">Apply</button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2"><input type="checkbox" id="selectAll"></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Listing</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($vehicles as $vehicle)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2"><input type="checkbox" name="ids[]" value="{{ $vehicle->id }}" class="rowCheckbox"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $vehicles->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</div>
                        <div class="text-xs text-gray-500">{{ $vehicle->license_plate ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $vehicle->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">Rp {{ number_format($vehicle->starting_price,0,',','.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs {{ $vehicle->status==='approved' ? 'bg-green-100 text-green-800' : ($vehicle->status==='pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($vehicle->status) }}</span>
                        @if($vehicle->is_featured ?? false)
                            <span class="ml-2 px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-800">Featured</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                        @if($vehicle->status === 'pending')
                            <form method="POST" action="{{ route('admin.vehicles.approve', $vehicle) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 mr-2">Approve</button>
                            </form>
                            <button type="button" onclick="openRejectModal({{ $vehicle->id }}, '{{ addslashes($vehicle->brand . ' ' . $vehicle->model) }}')" class="text-red-600">Reject</button>
                        @endif
                        <form method="POST" action="{{ route('admin.vehicles.toggle-feature', $vehicle) }}" class="inline ml-2">
                            @csrf
                            <button type="submit" class="text-indigo-600">{{ ($vehicle->is_featured ?? false) ? 'Unfeature' : 'Feature' }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No vehicles found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $vehicles->links() }}
    </div>

    <!-- Hidden bulk form -->
    <form method="POST" action="{{ route('admin.vehicles.bulk') }}" id="bulkForm" class="hidden">
        @csrf
        <input type="hidden" name="action" id="bulkFormAction">
        <input type="hidden" name="schedule_id" id="bulkFormSchedule">
        <div id="bulkFormIds"></div>
    </form>

    <!-- Reject modal (simple) -->
    <div id="rejectModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="font-bold text-lg mb-3">Reject Listing</h3>
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm text-gray-600">Reason</label>
                    <textarea name="rejection_reason" class="w-full border rounded px-2 py-2" rows="4" required></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Reject</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // Select all checkbox
    document.getElementById('selectAll')?.addEventListener('change', function(e){
        document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = e.target.checked);
    });

    // Bulk Apply button click handler
    document.getElementById('bulkApplyBtn')?.addEventListener('click', function(){
        const checkedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(cb => cb.value);
        
        if (checkedIds.length === 0) {
            alert('Pilih minimal 1 listing untuk tindakan massal.');
            return;
        }

        const action = document.getElementById('bulkAction')?.value;
        const schedule = document.getElementById('bulkSchedule')?.value;
        
        if (!action && !schedule) {
            alert('Pilih tindakan massal atau pilih schedule untuk ditugaskan.');
            return;
        }

        // Populate hidden form
        const form = document.getElementById('bulkForm');
        document.getElementById('bulkFormAction').value = action;
        document.getElementById('bulkFormSchedule').value = schedule;
        
        // Clear and add checked ids
        const idsContainer = document.getElementById('bulkFormIds');
        idsContainer.innerHTML = '';
        checkedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            idsContainer.appendChild(input);
        });

        // Set form action based on selection
        if (schedule && !action) {
            form.action = '{{ route('admin.vehicles.assign-schedule') }}';
        } else {
            form.action = '{{ route('admin.vehicles.bulk') }}';
        }

        form.submit();
    });

    function openRejectModal(id, title) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = '/admin/vehicles/' + id + '/reject';
        modal.classList.remove('hidden');
    }
    function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }
</script>

@endsection
