<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Information;
use App\Services\BookmarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::active()->get();
        $categories = Category::withCount('informations')->get();

        $search = $request->input('search');
        $categoryId = $request->input('category');

        $query = Information::published()->with(['category', 'author', 'versions']);

        if ($search) {
            $query->search($search);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $recentUpdates = $query->orderBy('published_at', 'desc')->paginate(6)->withQueryString();

        // Popular updates based on relation counts or simple ordering
        $popularUpdates = Information::published()
            ->withCount('bookmarks')
            ->orderBy('bookmarks_count', 'desc')
            ->take(4)
            ->get();

        $bookmarkedIds = [];
        if (Auth::check()) {
            $bookmarkedIds = Auth::user()->bookmarks()->pluck('information_id')->toArray();
        }

        // Check if ajax request for search/skeleton loading
        if ($request->ajax()) {
            return view('components.information-cards', [
                'informations' => $recentUpdates,
                'bookmarkedIds' => $bookmarkedIds
            ])->render();
        }

        return view('viewer.home', compact('banners', 'categories', 'recentUpdates', 'popularUpdates', 'bookmarkedIds'));
    }
}
