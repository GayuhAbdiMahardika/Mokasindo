<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VacancyController extends Controller
{
    public function index()
    {
        $vacancies = Vacancy::withCount('applications')->latest()->paginate(15);
        return view('admin.vacancies.index', compact('vacancies'));
    }

    public function create()
    {
        return view('admin.vacancies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full_time,part_time,contract,internship',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Vacancy::create($validated);

        return redirect()->route('admin.vacancies.index')->with('success', 'Lowongan kerja berhasil ditambahkan!');
    }

    public function edit(Vacancy $vacancy)
    {
        return view('admin.vacancies.edit', compact('vacancy'));
    }

    public function update(Request $request, Vacancy $vacancy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full_time,part_time,contract,internship',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vacancy->update($validated);

        return redirect()->route('admin.vacancies.index')->with('success', 'Lowongan kerja berhasil diupdate!');
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return redirect()->route('admin.vacancies.index')->with('success', 'Lowongan kerja berhasil dihapus!');
    }

    public function applications(Vacancy $vacancy)
    {
        $applications = $vacancy->applications()->with('vacancy')->latest()->paginate(15);
        return view('admin.vacancies.applications', compact('vacancy', 'applications'));
    }

    public function downloadCV(JobApplication $application)
    {
        if (!Storage::disk('public')->exists($application->cv_path)) {
            abort(404, 'CV tidak ditemukan');
        }

        return Storage::disk('public')->download($application->cv_path, $application->name . '_CV.pdf');
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,interview,accepted,rejected',
            'notes' => 'nullable|string',
        ]);

        $application->update($validated);

        return back()->with('success', 'Status lamaran berhasil diupdate!');
    }
}
