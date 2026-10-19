<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\InformationVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ViewerInformationController extends Controller
{
    public function show($slug)
    {
        $information = Information::published()
            ->with(['category', 'author', 'versions' => function ($q) {
                $q->orderBy('year', 'desc')->orderBy('month', 'desc')->with('attachments');
            }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Enforce backend validation
        Gate::authorize('view-information', $information);

        // Filter versions based on Gate view-version
        $allowedVersions = $information->versions->filter(function ($version) {
            return Gate::allows('view-version', $version);
        });

        if ($allowedVersions->isEmpty()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat versi histori dokumen ini.');
        }

        // Get the list of all available versions
        $availableVersions = $allowedVersions->map(function ($v) {
            return (object)[
                'month' => $v->month,
                'year' => $v->year
            ];
        });

        // Default version: the latest allowed one
        $activeVersion = $allowedVersions->first();

        $isBookmarked = false;
        if (Auth::check()) {
            $isBookmarked = Auth::user()->bookmarks()->where('information_id', $information->id)->exists();
        }

        return view('viewer.detail', compact('information', 'availableVersions', 'activeVersion', 'isBookmarked'));
    }

    public function fetchVersion(Request $request, $slug)
    {
        $information = Information::published()->where('slug', $slug)->firstOrFail();

        Gate::authorize('view-information', $information);

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

        // Enforce backend validation for version
        Gate::authorize('view-version', $version);

        // Generate clean response with secure streaming endpoint
        $attachments = $version->attachments->map(function ($att) {
            return [
                'id' => $att->id,
                'name' => $att->file_name,
                'url' => route('attachments.stream', $att->id),
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
