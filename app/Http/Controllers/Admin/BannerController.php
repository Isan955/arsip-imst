<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index()
    {
        $banners = Banner::orderBy('order', 'asc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|max:3072|mimes:jpeg,png,jpg,webp',
            'link_url' => 'nullable|url|max:255',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'image_path' => $imagePath,
            'link_url' => $data['link_url'] ?? null,
            'order' => $data['order'],
            'is_active' => $request->has('is_active'),
        ]);

        $this->auditService->logActivity(
            'create_banner',
            "Mengunggah banner baru: {$banner->title}"
        );

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:3072|mimes:jpeg,png,jpg,webp',
            'link_url' => 'nullable|url|max:255',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old
            Storage::disk('public')->delete($banner->image_path);
            $banner->image_path = $request->file('image')->store('banners', 'public');
        }

        $banner->title = $data['title'];
        $banner->description = $data['description'] ?? null;
        $banner->link_url = $data['link_url'] ?? null;
        $banner->order = $data['order'];
        $banner->is_active = $request->has('is_active');
        $banner->save();

        $this->auditService->logActivity(
            'update_banner',
            "Memperbarui banner ID {$banner->id}: {$banner->title}"
        );

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image_path);
        $title = $banner->title;
        $banner->delete();

        $this->auditService->logActivity(
            'delete_banner',
            "Menghapus banner: {$title}"
        );

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }
}
