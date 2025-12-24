@extends('admin.layout')

@section('title', __('admin.vehicles.title'))

@section('content')
    <div class="space-y-6" x-data="vehicleManager()">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">{{ __('admin.vehicles.heading') }}</h1>
            <form method="GET" action="{{ route('admin.vehicles.index') }}" class="flex items-center space-x-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('admin.vehicles.search_placeholder') }}" class="border rounded px-3 py-2" />
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">{{ __('admin.vehicles.all_status') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                        {{ __('admin.vehicles.status.draft') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        {{ __('admin.vehicles.status.pending') }}</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                        {{ __('admin.vehicles.status.approved') }}</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                        {{ __('admin.vehicles.status.rejected') }}</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>
                        {{ __('admin.vehicles.status.sold') }}</option>
                </select>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('admin.vehicles.filter') }}</button>
            </form>
        </div>

        <div class="flex items-center space-x-3 mb-4">
            <select name="action" id="bulkAction" class="border rounded px-3 py-2">
                <option value="">{{ __('admin.vehicles.bulk.action') }}</option>
                <option value="approve">{{ __('admin.vehicles.bulk.approve') }}</option>
                <option value="set_featured">{{ __('admin.vehicles.bulk.set_featured') }}</option>
                <option value="unset_featured">{{ __('admin.vehicles.bulk.unset_featured') }}</option>
            </select>

            <input type="number" name="duration_hours" id="bulkDuration" class="border rounded px-3 py-2 w-32"
                placeholder="Durasi (jam)" min="1" max="720" value="{{ $defaultDuration ?? 48 }}"
                title="Durasi lelang dalam jam (1-720)">

            <button type="button" id="bulkApplyBtn"
                class="px-4 py-2 bg-indigo-600 text-white rounded">{{ __('admin.vehicles.bulk.apply') }}</button>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2"><input type="checkbox" id="selectAll"></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('admin.vehicles.table.no') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('admin.vehicles.table.listing') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('admin.vehicles.table.user') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('admin.vehicles.table.price') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('admin.vehicles.table.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            {{ __('admin.vehicles.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><input type="checkbox" name="ids[]" value="{{ $vehicle->id }}"
                                    class="rowCheckbox"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                {{ $vehicles->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                                </div>
                                <div class="text-xs text-gray-500">{{ $vehicle->license_plate ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $vehicle->user->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">Rp
                                {{ number_format($vehicle->starting_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span
                                    class="px-2 py-1 rounded-full text-xs {{ $vehicle->status === 'approved' ? 'bg-green-100 text-green-800' : ($vehicle->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">{{ __('admin.vehicles.status.' . $vehicle->status) }}</span>
                                @if($vehicle->is_featured ?? false)
                                    <span
                                        class="ml-2 px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-800">{{ __('admin.vehicles.featured') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                    class="text-blue-600 hover:underline mr-3">{{ __('admin.vehicles.action.edit') }}</a>
                                @if($vehicle->status === 'pending')
                                    <button type="button"
                                        @click="openApproveModal({{ $vehicle->id }}, '{{ addslashes($vehicle->brand . ' ' . $vehicle->model) }}')"
                                        class="text-green-600 hover:text-green-800 mr-2">
                                        {{ __('admin.vehicles.action.approve') }}
                                    </button>
                                    <button type="button"
                                        onclick="openRejectModal({{ $vehicle->id }}, '{{ addslashes($vehicle->brand . ' ' . $vehicle->model) }}')"
                                        class="text-red-600 ml-2">{{ __('admin.vehicles.action.reject') }}</button>
                                @endif
                                <form method="POST" action="{{ route('admin.vehicles.toggle-feature', $vehicle) }}"
                                    class="inline ml-2">
                                    @csrf
                                    <button type="submit"
                                        class="text-indigo-600">{{ ($vehicle->is_featured ?? false) ? __('admin.vehicles.action.unfeature') : __('admin.vehicles.action.feature') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">{{ __('admin.vehicles.empty') }}</td>
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
            <input type="hidden" name="duration_hours" id="bulkFormDuration">
            <div id="bulkFormIds"></div>
        </form>

        <!-- Approve modal dengan durasi -->
        <div x-show="showApproveModal" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg p-6 w-96 shadow-xl">
                <h3 class="font-bold text-lg mb-3">Approve & Mulai Lelang</h3>
                <p class="text-sm text-gray-600 mb-4">Kendaraan: <strong x-text="approveVehicleName"></strong></p>
                <form :action="'/admin/vehicles/' + approveVehicleId + '/approve'" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Lelang (jam)</label>
                        <input type="number" name="duration_hours" class="w-full border rounded px-3 py-2" min="1" max="720"
                            value="{{ $defaultDuration ?? 48 }}" required>
                        <p class="text-xs text-gray-500 mt-1">Minimal 1 jam, maksimal 720 jam (30 hari)</p>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showApproveModal = false"
                            class="px-4 py-2 text-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Approve &
                            Mulai Lelang</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reject modal (simple) -->
        <div id="rejectModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h3 class="font-bold text-lg mb-3">{{ __('admin.vehicles.modal.reject_title') }}</h3>
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm text-gray-600">{{ __('admin.vehicles.modal.reason') }}</label>
                        <textarea name="rejection_reason" class="w-full border rounded px-2 py-2" rows="4"
                            required></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2">{{ __('admin.vehicles.modal.cancel') }}</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded">{{ __('admin.vehicles.modal.reject') }}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function vehicleManager() {
            return {
                showApproveModal: false,
                approveVehicleId: null,
                approveVehicleName: '',

                openApproveModal(id, name) {
                    this.approveVehicleId = id;
                    this.approveVehicleName = name;
                    this.showApproveModal = true;
                }
            }
        }

        // Select all checkbox
        document.getElementById('selectAll')?.addEventListener('change', function (e) {
            document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = e.target.checked);
        });

        // Bulk Apply button click handler
        document.getElementById('bulkApplyBtn')?.addEventListener('click', function () {
            const checkedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(cb => cb.value);

            if (checkedIds.length === 0) {
                alert('{{ __('admin.vehicles.alert.select_one') }}');
                return;
            }

            const action = document.getElementById('bulkAction')?.value;
            const duration = document.getElementById('bulkDuration')?.value;

            if (!action) {
                alert('{{ __('admin.vehicles.alert.choose_action') }}');
                return;
            }

            // Populate hidden form
            const form = document.getElementById('bulkForm');
            document.getElementById('bulkFormAction').value = action;
            document.getElementById('bulkFormDuration').value = duration;

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

            form.action = '{{ route('admin.vehicles.bulk') }}';
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

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

@endsection