<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LoginLog;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function activityLogs()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.logs.activity', compact('logs'));
    }

    public function loginLogs()
    {
        $logs = LoginLog::with('user')->orderBy('login_at', 'desc')->paginate(20);
        return view('admin.logs.login', compact('logs'));
    }

    public function dbBackupRestore()
    {
        return view('admin.logs.database');
    }

    public function backup()
    {
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        
        $filename = "backup-" . now()->format('Y-m-d-H-i-s') . ".sql";
        $storagePath = storage_path("app/private/" . $filename);

        // Ensure private folder exists
        if (!file_exists(storage_path("app/private"))) {
            mkdir(storage_path("app/private"), 0755, true);
        }

        // Run mysqldump using XAMPP absolute path
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';
        
        // Command syntax for Windows
        $passwordOption = $dbPass ? "-p\"{$dbPass}\"" : "";
        $cmd = "\"{$mysqldumpPath}\" -u {$dbUser} {$passwordOption} {$dbName} > \"{$storagePath}\"";

        exec($cmd, $output, $resultCode);

        if ($resultCode === 0 && file_exists($storagePath)) {
            $this->auditService->logActivity('database_backup', 'Berhasil mencadangkan basis data: ' . $filename);
            return Response::download($storagePath)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Gagal mencadangkan database. Silakan periksa konfigurasi server.');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:20480',
        ]);

        $file = $request->file('backup_file');
        $storagePath = $file->storeAs('temp', 'restore.sql', 'local');
        $absolutePath = storage_path('app/private/' . $storagePath);

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $mysqlPath = 'C:\xampp\mysql\bin\mysql.exe';
        $passwordOption = $dbPass ? "-p\"{$dbPass}\"" : "";
        
        $cmd = "\"{$mysqlPath}\" -u {$dbUser} {$passwordOption} {$dbName} < \"{$absolutePath}\"";

        exec($cmd, $output, $resultCode);

        // Clean up temp file
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }

        if ($resultCode === 0) {
            $this->auditService->logActivity('database_restore', 'Berhasil memulihkan basis data dari berkas eksternal.');
            return redirect()->back()->with('success', 'Database berhasil dipulihkan.');
        }

        return redirect()->back()->with('error', 'Gagal memulihkan database. Kode kesalahan: ' . $resultCode);
    }
}
