<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\InformationVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewerInformationController extends Controller
{
    public function show($slug)
    {
        $information = Information::published()
            ->with(['category', 'author', 'versions' => function ($q) {
                $q->orderBy('year', 'desc')->orderBy('month', 'desc');
            }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Get the list of all available versions
        $availableVersions = $information->versions()
            ->select('month', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Default version: the latest one
        $activeVersion = $information->versions()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->with('attachments')
            ->first();

        $isBookmarked = false;
        if (Auth::check()) {
            $isBookmarked = Auth::user()->bookmarks()->where('information_id', $information->id)->exists();
        }

        return view('viewer.detail', compact('information', 'availableVersions', 'activeVersion', 'isBookmarked'));
    }

    public function fetchVersion(Request $request, $slug)
    {
        $information = Information::published()->where('slug', $slug)->firstOrFail();

        $month = $request->integer('month');
        $year = $request->integer('year');

        $version = $information->versions()
            ->where('month', $month)
            ->where('year', $year)
            ->with('attachments')
            ->first();

        if (!$version) {
            return response()->json([
                'success' => false,
                'message' => 'Versi untuk bulan/tahun yang dipilih tidak ditemukan.'
            ], 404);
        }

        // Generate clean response or rendered partial
        $attachments = $version->attachments->map(function ($att) {
            return [
                'id' => $att->id,
                'name' => $att->file_name,
                'url' => asset('storage/' . $att->file_path),
                'size' => round($att->file_size / 1024, 2) . ' KB'
            ];
        });

        return response()->json([
            'success' => true,
            'rich_content' => $version->rich_content,
            'attachments' => $attachments
        ]);
    }
}
