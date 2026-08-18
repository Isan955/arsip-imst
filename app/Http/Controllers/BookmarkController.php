<?php

namespace App\Http\Controllers;

use App\Services\BookmarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    protected $bookmarkService;

    public function __construct(BookmarkService $bookmarkService)
    {
        $this->bookmarkService = $bookmarkService;
    }

    public function index()
    {
        $bookmarks = $this->bookmarkService->getUserBookmarks(Auth::id());
        return view('viewer.bookmarks', compact('bookmarks'));
    }

    public function toggle($informationId)
    {
        $bookmarked = $this->bookmarkService->toggleBookmark($informationId, Auth::id());

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmarked,
            'message' => $bookmarked ? 'Ditambahkan ke bookmark.' : 'Dihapus dari bookmark.'
        ]);
    }
}
