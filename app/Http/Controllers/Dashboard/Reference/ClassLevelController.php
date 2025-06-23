<?php

namespace App\Http\Controllers\Dashboard\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassLevel\ClassLevelRequest;
use App\Http\Requests\ClassLevel\UpdateClassLevelRequest;
use App\Models\ClassLevel;
use App\Services\Reference\ClassLevelService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ClassLevelController extends Controller implements HasMiddleware
{
    use ApiResponse;

    protected ClassLevelService $classLevelService;

    /**
     * @param ClassLevelService $classLevelService
     */
    public function __construct(ClassLevelService $classLevelService)
    {
        $this->classLevelService = $classLevelService;
    }

    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('class-level-read'), only: ['index']),
            new Middleware(PermissionMiddleware::using('class-level-write'), only: ['store', 'update']),
            new Middleware(PermissionMiddleware::using('class-level-delete'), only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $title = 'Level Kelas';

        return \view('dashboard.references.class-level.index', compact('title'));
    }

    public function datatable(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->ajax()) {
                $data = ClassLevel::query()
                    ->orderByDesc('serial_number');

                return DataTables::eloquent($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        $search = $request->get('search');

                        $instance->when($search, function ($query) use ($search) {
                            $query->whereAny(['serial_number', 'name'], 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->addColumn('is_active', function ($row) {
                        return '<span class="badge rounded-pill '. ($row->is_active ? 'bg-primary' : 'bg-danger') .'">'. ($row->is_active ? 'Aktif' : 'Tidak Aktif') .'</span>';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<button type="button" data-slug="' . $row->slug . '" data-serial-number="' . $row->serial_number . '" data-name="' . $row->name . '" data-active="' . $row->is_active . '" class="btn btn-icon btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="mdi mdi-pencil"></i></button> ';
                        $btn .= '<button type="button" data-slug="' . $row->slug . '" class="delete btn btn-icon btn-sm btn-danger"><i class="mdi mdi-trash-can-outline"></i></button>';

                        return $btn;
                    })
                    ->rawColumns(['is_active', 'action'])
                    ->make();
            }
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return response()->json(true);
    }

    public function store(ClassLevelRequest $request): RedirectResponse
    {
        try {
            $classLevel = new ClassLevel();
            $classLevel->serial_number = $request->input('serial_number');
            $classLevel->name = $request->input('name');
            $classLevel->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function update(UpdateClassLevelRequest $request, ClassLevel $classLevel): JsonResponse
    {
        try {
            $classLevel->serial_number = $request->input('serial_number');
            $classLevel->name = $request->input('name');
            $classLevel->is_active = $request->input('is_active');
            $classLevel->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal disimpan!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil disimpan!', null, null, Response::HTTP_OK);
    }

    public function destroy(ClassLevel $classLevel): JsonResponse
    {
        try {
            $classLevel->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal dihapus!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil dihapus!', null, null, Response::HTTP_OK);
    }

    public function select(Request $request)
    {
        return $this->classLevelService->selectClassLevel($request);
    }
}
