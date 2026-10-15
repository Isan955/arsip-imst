<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupAccessRule;
use App\Models\User;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index()
    {
        $groups = Group::withCount(['users', 'accessRules'])
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        $viewers = User::role('Viewer')->orderBy('name', 'asc')->get();
        return view('admin.groups.create', compact('viewers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'access_mode' => 'required|in:all,categories,custom',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'access_mode' => $request->access_mode,
        ]);

        if ($request->has('members')) {
            $group->users()->sync($request->members);
        }

        $this->auditService->logActivity(
            'create_group',
            "Membuat Group User baru: {$group->name}"
        );

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group User berhasil dibuat.');
    }

    public function edit(Group $group)
    {
        $viewers = User::role('Viewer')->orderBy('name', 'asc')->get();
        $selectedMemberIds = $group->users()->pluck('users.id')->toArray();

        return view('admin.groups.edit', compact('group', 'viewers', 'selectedMemberIds'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'access_mode' => 'required|in:all,categories,custom',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'access_mode' => $request->access_mode,
        ]);

        $group->users()->sync($request->input('members', []));

        $this->auditService->logActivity(
            'update_group',
            "Memperbarui data Group User: {$group->name}"
        );

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group User berhasil diperbarui.');
    }

    public function destroy(Group $group)
    {
        $name = $group->name;
        $group->delete();

        $this->auditService->logActivity(
            'delete_group',
            "Menghapus Group User: {$name}"
        );

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group User berhasil dihapus.');
    }

    // --- Granular Group Access Tree Management ---

    public function manageAccess(Group $group)
    {
        $categories = Category::with(['informations' => function ($qi) {
            $qi->published()->with(['versions' => function ($qv) {
                $qv->orderBy('year', 'desc')->orderBy('month', 'desc');
            }]);
        }])->orderBy('name', 'asc')->get();

        $assignedCategories = $group->accessRules()
            ->where('accessible_type', Category::class)
            ->pluck('accessible_id')
            ->toArray();

        $assignedInformations = $group->accessRules()
            ->where('accessible_type', Information::class)
            ->pluck('accessible_id')
            ->toArray();

        $assignedVersions = $group->accessRules()
            ->where('accessible_type', InformationVersion::class)
            ->pluck('accessible_id')
            ->toArray();

        return view('admin.groups.access', compact(
            'group',
            'categories',
            'assignedCategories',
            'assignedInformations',
            'assignedVersions'
        ));
    }

    public function updateAccess(Request $request, Group $group)
    {
        $request->validate([
            'access_mode' => 'required|in:all,categories,custom',
            'categories' => 'nullable|array',
            'informations' => 'nullable|array',
            'versions' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $group) {
            $group->access_mode = $request->access_mode;
            $group->save();

            $group->accessRules()->delete();

            if ($request->access_mode === 'categories') {
                if ($request->has('categories')) {
                    foreach ($request->input('categories') as $catId) {
                        GroupAccessRule::create([
                            'group_id' => $group->id,
                            'accessible_id' => $catId,
                            'accessible_type' => Category::class
                        ]);
                    }
                }
            } elseif ($request->access_mode === 'custom') {
                if ($request->has('categories')) {
                    foreach ($request->input('categories') as $catId) {
                        GroupAccessRule::create([
                            'group_id' => $group->id,
                            'accessible_id' => $catId,
                            'accessible_type' => Category::class
                        ]);
                    }
                }
                if ($request->has('informations')) {
                    foreach ($request->input('informations') as $infoId) {
                        GroupAccessRule::create([
                            'group_id' => $group->id,
                            'accessible_id' => $infoId,
                            'accessible_type' => Information::class
                        ]);
                    }
                }
                if ($request->has('versions')) {
                    foreach ($request->input('versions') as $versionId) {
                        GroupAccessRule::create([
                            'group_id' => $group->id,
                            'accessible_id' => $versionId,
                            'accessible_type' => InformationVersion::class
                        ]);
                    }
                }
            }
        });

        $this->auditService->logActivity(
            'update_group_access',
            "Memperbarui hak akses data Group User: {$group->name} (Mode: {$request->access_mode})"
        );

        return redirect()->route('admin.groups.index')
            ->with('success', 'Hak akses Group User berhasil diperbarui.');
    }
}
