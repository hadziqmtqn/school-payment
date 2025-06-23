<?php

namespace App\Http\Controllers\Dashboard\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassLevel\SubClassLevelRequest;
use App\Models\SubClassLevel;
use App\Services\Reference\ClassLevelService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubClassLevelController extends Controller implements HasMiddleware
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
            new Middleware(PermissionMiddleware::using('sub-class-level-write'), only: ['store', 'update']),
            new Middleware(PermissionMiddleware::using('sub-class-level-delete'), only: ['destroy']),
        ];
    }

    public function datatable(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->ajax()) {
                $data = SubClassLevel::query()
                    ->orderByDesc('created_at');

                return DataTables::eloquent($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        $search = $request->get('search');

                        $instance->when($search, function ($query) use ($search) {
                            $query->whereAny(['name'], 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<button type="button" data-slug="' . $row->slug . '" data-name="' . $row->name . '" data-active="' . $row->is_active . '" class="btn btn-icon btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditSubClassLevel"><i class="mdi mdi-pencil"></i></button> ';
                        $btn .= '<button type="button" data-slug="' . $row->slug . '" class="delete btn btn-icon btn-sm btn-danger"><i class="mdi mdi-trash-can-outline"></i></button>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make();
            }
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return response()->json(true);
    }

    public function store(SubClassLevelRequest $request): RedirectResponse
    {
        try {
            $subClassLevel = new SubClassLevel();
            $subClassLevel->name = $request->input('name');
            $subClassLevel->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function update(SubClassLevelRequest $request, SubClassLevel $subClassLevel): JsonResponse
    {
        try {
            $subClassLevel->name = $request->input('name');
            $subClassLevel->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal disimpan!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil disimpan!', null, null, Response::HTTP_OK);
    }

    public function destroy(SubClassLevel $subClassLevel): JsonResponse
    {
        try {
            $subClassLevel->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal dihapus!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil dihapus!', null, null, Response::HTTP_OK);
    }

    public function select(Request $request)
    {
        return $this->classLevelService->selectSubClassLevel($request);
    }
}
