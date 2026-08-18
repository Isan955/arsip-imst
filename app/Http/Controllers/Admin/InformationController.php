<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Models\Attachment;
use App\Http\Requests\InformationStoreRequest;
use App\Http\Requests\InformationUpdateRequest;
use App\Http\Requests\VersionStoreRequest;
use App\Services\InformationService;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    protected $infoService;
    protected $auditService;

    public function __construct(InformationService $infoService, AuditLogService $auditService)
    {
        $this->infoService = $infoService;
        $this->auditService = $auditService;
    }

    public function index(Request $request)
    {
        $categories = Category::all();
        $informations = $this->infoService->listAll($request->only(['search', 'category_id', 'status']));

        return view('admin.informations.index', compact('informations', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.informations.create', compact('categories'));
    }

    public function store(InformationStoreRequest $request)
    {
        $information = $this->infoService->createInformation($request->validated(), Auth::id());

        $this->auditService->logActivity(
            'create_information',
            "Membuat dokumen informasi baru: {$information->title} (Status: {$information->status})"
        );

        return redirect()->route('admin.informations.index')
            ->with('success', 'Informasi berhasil dibuat.');
    }

    public function edit(Information $information)
    {
        $categories = Category::all();
        return view('admin.informations.edit', compact('information', 'categories'));
    }

    public function update(InformationUpdateRequest $request, Information $information)
    {
        $this->infoService->updateInformation($information, $request->validated(), Auth::id());

        $this->auditService->logActivity(
            'update_information',
            "Memperbarui data informasi ID {$information->id}: {$information->title}"
        );

        return redirect()->route('admin.informations.index')
            ->with('success', 'Informasi berhasil diperbarui.');
    }

    public function destroy(Information $information)
    {
        $title = $information->title;
        $this->infoService->deleteInformation($information);

        $this->auditService->logActivity(
            'delete_information',
            "Menghapus informasi: {$title}"
        );

        return redirect()->route('admin.informations.index')
            ->with('success', 'Informasi berhasil dihapus.');
    }

    // --- Version Management ---

    public function versions(Information $information)
    {
        $versions = $information->versions()
            ->with('attachments', 'creator')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        return view('admin.informations.versions.index', compact('information', 'versions'));
    }

    public function createVersion(Information $information)
    {
        return view('admin.informations.versions.create', compact('information'));
    }

    public function storeVersion(VersionStoreRequest $request, Information $information)
    {
        $version = $this->infoService->createOrUpdateVersion(
            $information->id,
            $request->validated(),
            Auth::id(),
            $request->file('attachment_file')
        );

        $this->auditService->logActivity(
            'create_version',
            "Membuat histori bulanan untuk informasi {$information->title} pada bulan {$version->month}/{$version->year}"
        );

        return redirect()->route('admin.informations.versions', $information->id)
            ->with('success', 'Versi bulanan berhasil ditambahkan.');
    }

    public function editVersion(Information $information, InformationVersion $version)
    {
        return view('admin.informations.versions.edit', compact('information', 'version'));
    }

    public function updateVersion(VersionStoreRequest $request, Information $information, InformationVersion $version)
    {
        $this->infoService->createOrUpdateVersion(
            $information->id,
            $request->validated(),
            Auth::id(),
            $request->file('attachment_file')
        );

        $this->auditService->logActivity(
            'update_version',
            "Memperbarui histori bulanan untuk informasi {$information->title} pada bulan {$version->month}/{$version->year}"
        );

        return redirect()->route('admin.informations.versions', $information->id)
            ->with('success', 'Versi bulanan berhasil diperbarui.');
    }

    public function destroyVersion(Information $information, InformationVersion $version)
    {
        // Delete attachments
        foreach ($version->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $monthYear = "{$version->month}/{$version->year}";
        $version->delete();

        $this->auditService->logActivity(
            'delete_version',
            "Menghapus histori bulanan {$monthYear} untuk informasi {$information->title}"
        );

        return redirect()->route('admin.informations.versions', $information->id)
            ->with('success', 'Versi bulanan berhasil dihapus.');
    }
}
