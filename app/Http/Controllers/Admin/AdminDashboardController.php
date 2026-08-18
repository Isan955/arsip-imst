<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Information;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalInfo = Information::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        
        $publishedCount = Information::where('status', 'published')->count();
        $draftCount = Information::where('status', 'draft')->count();

        // Recent activity logs (limit 10, with user details)
        $recentLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        // Data for monthly update chart (number of information_versions created per month/year)
        // SQL query grouping by month, year
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
            'publishedCount',
            'draftCount',
            'recentLogs',
            'monthlyUpdates'
        ));
    }
}
