<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Page;
use App\Models\Team;
use App\Models\Faq;
use App\Models\Vacancy;
use App\Models\Inquiry;
use App\Models\JobApplication;

class CompanyController extends Controller
{
    // Halaman About Us
    public function about()
    {
        $page = Page::findBySlug('about-us'); // Ambil konten teks
        $teams = Team::orderBy('order_number')->get(); // Ambil data tim
        return view('pages.company.about', compact('page', 'teams'));
    }

    // Halaman FAQ
    public function faq()
    {
        $faqs = Faq::active()->get()->groupBy('category');
        return view('pages.company.faq', compact('faqs'));
    }

    // Halaman Karir (List)
    public function career()
    {
        $vacancies = Vacancy::orderByDesc('is_active')
            ->latest()
            ->get();
        return view('pages.company.career', compact('vacancies'));
    }

    // Halaman Detail Karir
    public function careerDetail($id)
    {
        $vacancy = Vacancy::findOrFail($id);
        return view('pages.company.career-detail', compact('vacancy'));
    }

    // Logika Upload Karir
    public function storeCareerApplication(Request $request, $id)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cover_letter' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf|max:2048', // Wajib PDF, Max 2MB
        ]);

        // 2. Upload File CV
        if ($request->hasFile('cv')) {
            // Simpan ke folder: storage/app/public/cvs
            $filePath = $request->file('cv')->store('cvs', 'public');
        }

        // 3. Simpan ke Database
        JobApplication::create([
            'vacancy_id' => $id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'cover_letter' => $validated['cover_letter'] ?? null,
            'cv_path' => $filePath,
            'status' => 'pending'
        ]);

        // 4. Pesan Sukses
        return back()->with('success', 'Lamaran berhasil dikirim! Tim HRD kami akan segera mereview CV Anda.');
    }

    // Halaman Contact (Tampilkan Form)
    public function contact()
    {
        return view('pages.company.contact');
    }

    // Proses Kirim Pesan Contact (POST)
    public function storeContact(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Simpan ke database
        Inquiry::create($validated);

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan membalas via email.');
    }
}
