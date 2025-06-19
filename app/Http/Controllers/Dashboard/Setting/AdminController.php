<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller implements HasMiddleware
{
    use ApiResponse;

    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('admin-read'), only: ['index']),
            new Middleware(PermissionMiddleware::using('admin-write'), only: ['store', 'update']),
            new Middleware(PermissionMiddleware::using('admin-delete'), only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $title = 'Admin';

        return \view('dashboard.setting.admin.index', compact('title'));
    }

    public function datatable(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->ajax()) {
                $data = User::query()
                    ->with('admin')
                    ->whereHas('admin')
                    ->when($request->get('status'), function ($query) use ($request) {
                        $query->when($request->get('status') != 'deleted', function ($query) use ($request) {
                            $query->where('is_active', ($request->get('status') == 'active'));
                        });

                        $query->when($request->get('status') == 'deleted', function ($query) use ($request) {
                            $query->onlyTrashed();
                        });
                    });

                return DataTables::eloquent($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        $search = $request->get('search');

                        $instance->when($search, function ($query) use ($search) {
                            $query->whereAny(['name', 'email'], 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->addColumn('role', fn($row) => '<span class="badge rounded-pill ' . ($row->roles->first()->name == 'super_admin' ? 'bg-primary' : 'bg-secondary') . '">'. ucfirst(str_replace('_', ' ', $row->roles->first()->name)) .'</span>')
                    ->addColumn('whatsappNumber', fn($row) => $row->admin?->whatsapp_number)
                    ->addColumn('markAsContact', fn($row) => $row->admin?->mark_as_contact ? 'Ya' : 'Bukan')
                    ->addColumn('is_active', function ($row) {
                        return '<span class="badge rounded-pill '. ($row->is_active ? 'bg-primary' : 'bg-danger') .'">'. ($row->is_active ? 'Aktif' : 'Tidak Aktif') .'</span>';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = null;

                        if (!$row->deleted_at) {
                            $btn = '<a href="' . route('admin.show', $row->username) . '" class="btn btn-icon btn-sm btn-primary"><i class="mdi mdi-eye"></i></a> ';
                            if ($row->roles->first()->name != 'super_admin') {
                                $btn .= '<button type="button" data-username="' . $row->username . '" class="delete btn btn-icon btn-sm btn-danger"><i class="mdi mdi-trash-can-outline"></i></button>';
                            }
                        }else {
                            $btn .= '<button type="button" data-username="' . $row->username . '" class="restore btn btn-icon btn-sm btn-warning"><i class="mdi mdi-restore-alert"></i></button> ';
                            $btn .= '<button type="button" data-username="' . $row->username . '" class="force-delete btn btn-sm btn-danger"><i class="mdi mdi-trash-can-outline me-1"></i>Hapus Permanen</button>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['is_active', 'role', 'action'])
                    ->make();
            }
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return response()->json(true);
    }

    /**
     * @throws Throwable
     */
    public function store(AdminRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            $user->assignRole('admin');

            $admin = new Admin();
            $admin->user_id = $user->id;
            $admin->whatsapp_number = $request->input('whatsapp_number');
            $admin->mark_as_contact = $request->input('mark_as_contact');
            $admin->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function show(User $user): View
    {
        Gate::authorize('show', $user);

        $title = 'Admin';
        $subTitle = 'Detail Admin';
        $user->load('admin');

        return \view('dashboard.setting.admin.show', compact('title', 'subTitle', 'user'));
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateAdminRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        try {
            $user->load('admin');

            DB::beginTransaction();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $request->input('password') ? Hash::make($request->input('password')) : $user->password;
            $user->is_active = $user->hasRole('admin') ? $request->input('is_active') : true;
            $user->save();

            $admin = $user->admin;
            $admin->user_id = $user->id;
            $admin->whatsapp_number = $request->input('whatsapp_number');
            $admin->mark_as_contact = $request->input('mark_as_contact');
            $admin->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    /**
     * @throws Throwable
     */
    public function destroy(User $user): JsonResponse
    {
        Gate::authorize('destroy', $user);

        try {
            DB::beginTransaction();
            $user->is_active = false;
            $user->save();

            $user->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal disimpan!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil disimpan!', null, null, Response::HTTP_OK);
    }

    public function restore($username): JsonResponse
    {
        try {
            $user = User::onlyTrashed()
                ->filterByUsername($username)
                ->firstOrFail();

            $user->restore();
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal dikembalikan', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil dikembalikan', null, null, Response::HTTP_OK);
    }

    /**
     * @throws Throwable
     */
    public function forceDelete($username): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = User::onlyTrashed()
                ->filterByUsername($username)
                ->firstOrFail();

            if ($user->hasMedia('photo')) $user->clearMediaCollection('photo');

            $user->forceDelete();
            DB::commit();
        }catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal dihapus permanen', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil dihapus permanen', null, null, Response::HTTP_OK);
    }
}
