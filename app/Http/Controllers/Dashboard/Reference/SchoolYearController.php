<?php

namespace App\Http\Controllers\Dashboard\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolYear\SchoolYearRequest;
use App\Http\Requests\SchoolYear\UpdateSchoolYearRequest;
use App\Models\SchoolYear;
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

class SchoolYearController extends Controller implements HasMiddleware
{
    use ApiResponse;

    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('school-year-read'), only: ['index']),
            new Middleware(PermissionMiddleware::using('school-year-write'), only: ['store', 'update'])
        ];
    }

    public function index(): View
    {
        $title = 'Tahun Ajaran';

        return \view('dashboard.references.school-year.index', compact('title'));
    }

    public function datatable(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->ajax()) {
                $data = SchoolYear::query()
                    ->orderByDesc('last_year');

                return DataTables::eloquent($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        $search = $request->get('search');

                        $instance->when($search, function ($query) use ($search) {
                            $query->whereAny(['first_year', 'last_year'], 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->addColumn('year', fn($row) => $row->year)
                    ->addColumn('is_active', function ($row) {
                        return '<span class="badge rounded-pill '. ($row->is_active ? 'bg-primary' : 'bg-danger') .'">'. ($row->is_active ? 'Aktif' : 'Tidak Aktif') .'</span>';
                    })
                    ->addColumn('action', function ($row) {
                        return '<button type="button" data-slug="' . $row->slug . '" data-first-year="' . $row->first_year . '" data-last-year="' . $row->last_year . '" data-active="' . $row->is_active . '" class="btn btn-icon btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="mdi mdi-pencil"></i></button>';
                    })
                    ->rawColumns(['is_active', 'action'])
                    ->make();
            }
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return response()->json(true);
    }

    public function store(SchoolYearRequest $request): RedirectResponse
    {
        try {
            $schoolYear = new SchoolYear();
            $schoolYear->first_year = $request->input('first_year');
            $schoolYear->last_year = $request->input('last_year');
            $schoolYear->is_active = $request->input('is_active');
            $schoolYear->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function update(UpdateSchoolYearRequest $request, SchoolYear $schoolYear): JsonResponse
    {
        try {
            if ($schoolYear->is_active && $request->input('is_active') == 0) {
                return $this->apiResponse('Tidak bisa mengubah menjadi tidak aktif', null, null, Response::HTTP_BAD_REQUEST);
            }

            $schoolYear->first_year = $request->input('first_year');
            $schoolYear->last_year = $request->input('last_year');
            $schoolYear->is_active = $request->input('is_active');
            $schoolYear->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal disimpan!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil disimpan!', null, null, Response::HTTP_OK);
    }
}
