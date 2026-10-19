<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Models\User;
use App\Models\Group;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalInfo = Information::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalGroups = Group::count();
        $totalVersions = InformationVersion::count();
        
        $publishedCount = Information::where('status', 'published')->count();
        $draftCount = Information::where('status', 'draft')->count();

        // Recent activity logs (limit 8, with user details)
        $recentLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->take(8)->get();

        // Recent documents for quick monitoring
        $recentInformations = Information::with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data for monthly update chart (number of information_versions created per month/year)
        $monthlyUpdates = DB::table('information_versions')
            ->select('month', 'year', DB::raw('count(*) as total'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->reverse();

        return view('admin.dashboard', compact(
            'totalInfo',
            'totalCategories',
            'totalUsers',
            'totalGroups',
            'totalVersions',
            'publishedCount',
            'draftCount',
            'recentLogs',
            'recentInformations',
            'monthlyUpdates'
        ));
    }
}
