<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Information;
use App\Models\InformationVersion;
use App\Models\UserAccessRule;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index()
    {
        $users = User::with('roles')->orderBy('name', 'asc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['Admin Kesekretariatan', 'Viewer'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->has('is_active'),
            'access_mode' => 'custom', // Default: no access until explicitly granted by Super Admin
        ]);

        $user->assignRole($request->role);

        $this->auditService->logActivity(
            'create_user',
            "Membuat user baru: {$user->name} dengan role {$request->role}"
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::whereIn('name', ['Admin Kesekretariatan', 'Viewer'])->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = $request->has('is_active');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $this->auditService->logActivity('reset_password', "Mengatur ulang kata sandi untuk user: {$user->name}");
        }

        $user->save();

        // Sync Spatie Role
        $user->syncRoles([$request->role]);

        $this->auditService->logActivity(
            'update_user',
            "Memperbarui data user: {$user->name}"
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        $this->auditService->logActivity(
            'delete_user',
            "Menghapus user: {$name}"
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    // --- Granular Access Level Controller Logic ---

    public function manageAccess(User $user)
    {
        // Access Tree-view data
        $categories = Category::with(['informations' => function ($qi) {
            $qi->published()->with(['versions' => function ($qv) {
                $qv->orderBy('year', 'desc')->orderBy('month', 'desc');
            }]);
        }])->orderBy('name', 'asc')->get();

        // Currently configured access rules IDs
        $assignedCategories = $user->accessRules()
            ->where('accessible_type', Category::class)
            ->pluck('accessible_id')
            ->toArray();

        $assignedInformations = $user->accessRules()
            ->where('accessible_type', Information::class)
            ->pluck('accessible_id')
            ->toArray();

        $assignedVersions = $user->accessRules()
            ->where('accessible_type', InformationVersion::class)
            ->pluck('accessible_id')
            ->toArray();

        return view('admin.users.access', compact(
            'user',
            'categories',
            'assignedCategories',
            'assignedInformations',
            'assignedVersions'
        ));
    }

    public function updateAccess(Request $request, User $user)
    {
        $request->validate([
            'access_mode' => 'required|in:all,categories,custom',
            'categories' => 'nullable|array',
            'informations' => 'nullable|array',
            'versions' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $user) {
            // Update User Access Mode
            $user->access_mode = $request->access_mode;
            $user->save();

            // Clear old rules
            $user->accessRules()->delete();

            if ($request->access_mode === 'categories') {
                if ($request->has('categories')) {
                    foreach ($request->input('categories') as $catId) {
                        UserAccessRule::create([
                            'user_id' => $user->id,
                            'accessible_id' => $catId,
                            'accessible_type' => Category::class
                        ]);
                    }
                }
            } elseif ($request->access_mode === 'custom') {
                // Save categories
                if ($request->has('categories')) {
                    foreach ($request->input('categories') as $catId) {
                        UserAccessRule::create([
                            'user_id' => $user->id,
                            'accessible_id' => $catId,
                            'accessible_type' => Category::class
                        ]);
                    }
                }
                // Save informations
                if ($request->has('informations')) {
                    foreach ($request->input('informations') as $infoId) {
                        UserAccessRule::create([
                            'user_id' => $user->id,
                            'accessible_id' => $infoId,
                            'accessible_type' => Information::class
                        ]);
                    }
                }
                // Save versions
                if ($request->has('versions')) {
                    foreach ($request->input('versions') as $versionId) {
                        UserAccessRule::create([
                            'user_id' => $user->id,
                            'accessible_id' => $versionId,
                            'accessible_type' => InformationVersion::class
                        ]);
                    }
                }
            }
        });

        $this->auditService->logActivity(
            'update_user_access',
            "Memperbarui hak akses granular data untuk user: {$user->name} (Mode: {$request->access_mode})"
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'Hak akses granular user berhasil diperbarui.');
    }
}
