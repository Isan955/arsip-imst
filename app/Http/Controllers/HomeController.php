<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Services\BookmarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::active()->get();
        $user = Auth::user();
        $hasFullAccess = !$user || $user->hasFullAccess();

        $allowedCatIds = $hasFullAccess ? [] : $user->getAllowedCategoryIds();
        $allowedInfoIds = $hasFullAccess ? [] : $user->getAllowedInformationIds();

        // 1. Filter Categories list (only categories that user has access to)
        $categoriesQuery = Category::query();
        if (!$hasFullAccess) {
            $categoriesQuery->whereIn('id', $allowedCatIds);
        }
        
        $categories = $categoriesQuery->withCount(['informations' => function($qi) use ($hasFullAccess, $allowedInfoIds) {
            $qi->where('status', 'published');
            if (!$hasFullAccess) {
                $qi->whereIn('id', $allowedInfoIds);
            }
        }])->get();

        $totalAccessibleInformations = $hasFullAccess 
            ? Information::published()->count() 
            : count($allowedInfoIds);

        $search = $request->input('search');
        $categoryId = $request->input('category');

        // 2. Filter Information Query list
        $query = Information::published()->with(['category', 'author', 'versions']);

        if (!$hasFullAccess) {
            $query->whereIn('id', $allowedInfoIds);
        }

        if ($search) {
            $query->search($search);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $recentUpdates = $query->orderBy('published_at', 'desc')->paginate(6)->withQueryString();

        // 3. Filter Popular updates list
        $popQuery = Information::published()
            ->withCount('bookmarks')
            ->orderBy('bookmarks_count', 'desc');

        if (!$hasFullAccess) {
            $popQuery->whereIn('id', $allowedInfoIds);
        }
        $popularUpdates = $popQuery->take(4)->get();

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

        return view('viewer.home', compact(
            'banners',
            'categories',
            'recentUpdates',
            'popularUpdates',
            'bookmarkedIds',
            'totalAccessibleInformations'
        ));
    }
}
