@extends('admin.layout')

@section('title', 'Job Applications')
@section('page-title', 'Job Applications - ' . $vacancy->title)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.vacancies.index') }}" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Back to Vacancies
    </a>
    <h3 class="text-2xl font-bold text-gray-900 mt-4">Applications for: {{ $vacancy->title }}</h3>
    <p class="text-gray-600">{{ $vacancy->department }} • {{ $vacancy->location }}</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applicant</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($applications as $application)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $application->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $application->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $application->phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $application->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form method="POST" action="{{ route('admin.applications.update-status', $application) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="text-xs rounded-full px-3 py-1 font-semibold border-0 
                                {{ $application->status == 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $application->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $application->status == 'interview' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $application->status == 'reviewing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $application->status == 'pending' ? 'bg-gray-100 text-gray-800' : '' }}">
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewing" {{ $application->status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Interview</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.applications.download-cv', $application) }}" class="text-blue-600 hover:text-blue-900" title="Download CV">
                            <i class="fas fa-download"></i> CV
                        </a>
                        @if($application->cover_letter)
                            <button onclick="alert('{{ addslashes($application->cover_letter) }}')" class="text-green-600 hover:text-green-900" title="View Cover Letter">
                                <i class="fas fa-file-alt"></i> Letter
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No applications yet for this position.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $applications->links() }}
</div>
@endsection
