<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookController extends Controller
{
    public function showForm()
    {
        return view('facebook_form'); // pastikan file resources/views/facebook_form.blade.php ada
    }

    public function postImageUrl(Request $request)
    {
        $pageId = env('FACEBOOK_PAGE_ID');
        $token = env('FACEBOOK_PAGE_ACCESS_TOKEN');

        $response = Http::post("https://graph.facebook.com/{$pageId}/photos", [
            'url' => $request->input('image_url'),
            'caption' => $request->input('caption', ''),
            'access_token' => $token,
        ]);

        return response()->json($response->json());
    }

    public function postImageUpload(Request $request)
    {
        $pageId = env('FACEBOOK_PAGE_ID');
        $token = env('FACEBOOK_PAGE_ACCESS_TOKEN');

        $image = $request->file('image');
        if (!$image || !$image->isValid()) {
            return response()->json(['error' => 'No file uploaded or invalid file'], 422);
        }

        $response = Http::attach(
            'source',
            file_get_contents($image->getRealPath()),
            $image->getClientOriginalName()
        )->post("https://graph.facebook.com/{$pageId}/photos", [
            'caption' => $request->input('caption', ''),
            'access_token' => $token,
        ]);

        return response()->json($response->json());
    }
}
