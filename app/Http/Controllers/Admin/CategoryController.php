<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index()
    {
        $categories = Category::withCount('informations')->orderBy('name', 'asc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create($request->validated());

        $this->auditService->logActivity(
            'create_category',
            "Membuat kategori baru: {$category->name} (Slug: {$category->slug})"
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryStoreRequest $request, Category $category)
    {
        $category->update($request->validated());

        $this->auditService->logActivity(
            'update_category',
            "Mengubah kategori ID {$category->id} menjadi: {$category->name}"
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $catName = $category->name;
        $category->delete();

        $this->auditService->logActivity(
            'delete_category',
            "Menghapus kategori: {$catName}"
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
