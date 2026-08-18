<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\CompanyProfile;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index()
    {
        $settings = Setting::all();
        $profiles = CompanyProfile::all();
        return view('admin.settings.index', compact('settings', 'profiles'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'nullable|array',
            'profiles' => 'nullable|array',
        ]);

        if ($request->has('settings')) {
            foreach ($request->input('settings') as $key => $value) {
                Setting::setValue($key, $value);
            }
        }

        if ($request->has('profiles')) {
            foreach ($request->input('profiles') as $key => $value) {
                CompanyProfile::setValue($key, $value);
            }
        }

        $this->auditService->logActivity(
            'update_settings',
            'Memperbarui konfigurasi sistem dan profil perusahaan.'
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Konfigurasi berhasil disimpan.');
    }
}
