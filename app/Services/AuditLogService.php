<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\LoginLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function logActivity(string $action, ?string $description = null)
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function logLogin($userId)
    {
        return LoginLog::create([
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_at' => now(),
        ]);
    }

    public function logLogout($userId)
    {
        $lastLog = LoginLog::where('user_id', $userId)
            ->whereNull('logout_at')
            ->orderBy('login_at', 'desc')
            ->first();

        if ($lastLog) {
            $lastLog->update(['logout_at' => now()]);
        }
    }
}
