<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\UpdateRequest;
use App\Services\Setting\RoleService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ReflectionException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller implements HasMiddleware
{
    protected RoleService $roleService;

    /**
     * @param RoleService $roleService
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware('role:super_admin'),
        ];
    }

    public function index(): View
    {
        $title = 'Role';

        return view('dashboard.setting.role.index', compact('title'));
    }

    public function datatable(Request $request): JsonResponse
    {
        try {
            if ($request->ajax()) {
                $data = Role::query()
                    ->withCount('permissions');

                return DataTables::eloquent($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        $search = $request->get('search');

                        $instance->when($search, function ($query) use ($search) {
                            $query->whereAny(['name'], 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->addColumn('name', fn($row) => ucfirst(str_replace('_', ' ', $row->name)))
                    ->addColumn('userCount', function ($row) {
                        return '<span class="badge rounded-pill bg-label-warning">'. $row->users()->count() .'</span>';
                    })
                    ->addColumn('permissionCount', function ($row) {
                        return '<span class="badge rounded-pill bg-label-info">'. $row->permissions_count .'</span>';
                    })
                    ->addColumn('action', function ($row) {
                        return '<a href="' . route('role.edit', $row->slug) . '" class="btn btn-icon btn-sm btn-warning"><i class="mdi mdi-pencil"></i></a> ';
                    })
                    ->rawColumns(['userCount', 'action', 'permissionCount'])
                    ->make();
            }
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return response()->json(true);
    }

    /**
     * @throws ReflectionException
     */
    public function edit(Role $role): View
    {
        $title = 'Role';
        $role->load('permissions');
        $models = $this->roleService->getAllModelsFromClassmap();

        return view('dashboard.setting.role.edit', compact('title', 'role', 'models'));
    }

    public function update(UpdateRequest $request, Role $role): RedirectResponse
    {
        try {
            $permissions = $request->input('permissions', []);

            // Buat permission jika belum ada
            $permissionIds = [];
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)
                    ->first();

                if (!$permission) {
                    $permission = Permission::create([
                        'slug' => Str::uuid()->toString(),
                        'name' => $permissionName
                    ]);
                }

                $permissionIds[] = $permission->id; // Simpan ID permission untuk sinkronisasi
            }

            // Sinkronkan permission dengan role
            $role->syncPermissions($permissionIds);
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function select(Request $request)
    {
        return $this->roleService->select($request);
    }
}
