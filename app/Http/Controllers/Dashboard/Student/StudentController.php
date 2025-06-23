<?php

namespace App\Http\Controllers\Dashboard\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\DatatableRequest;
use App\Http\Requests\Student\StudentRequest;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentLevel;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('student-read'), only: ['index']),
            new Middleware(PermissionMiddleware::using('student-write'), only: ['store', 'update']),
        ];
    }

    public function index(): View
    {
        $title = 'Siswa';

        return \view('dashboard.student.index', compact('title'));
    }

    public function datatable(DatatableRequest $request): JsonResponse
    {
        try {
            if ($request->ajax()) {
                $data = User::query()
                    ->with([
                        'student.studentLevel' => function ($query) use ($request) {
                            $query->schoolYearId($request->input('school_year_id'));
                        },
                        'student.studentLevel.classLevel',
                        'student.studentLevel.subClassLevel'
                    ])
                    ->whereHas('student.studentLevel', function ($query) use ($request) {
                        $query->schoolYearId($request->input('school_year_id'))
                            ->filterData($request);
                    })
                    ->when($request->get('is_active'), function ($query) use ($request) {
                        $query->when($request->get('is_active') != 'deleted', function ($query) use ($request) {
                            $query->where('is_active', ($request->get('is_active') == 'active'));
                        });

                        $query->when($request->get('is_active') == 'deleted', function ($query) use ($request) {
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
                    ->addColumn('whatsappNumber', fn($row) => $row->student?->whatsapp_number)
                    ->addColumn('classLevel', fn($row) => $row->student?->studentLevel?->classLevel?->name . ' ' . $row->student?->studentLevel?->subClassLevel?->name)
                    ->addColumn('is_active', function ($row) {
                        return '<span class="badge rounded-pill '. ($row->is_active ? 'bg-primary' : 'bg-danger') .'">'. ($row->is_active ? 'Aktif' : 'Tidak Aktif') .'</span>';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = null;

                        if (!$row->deleted_at) {
                            $btn = '<a href="' . route('student.show', $row->username) . '" class="btn btn-icon btn-sm btn-primary"><i class="mdi mdi-eye"></i></a> ';
                            $btn .= '<button type="button" data-username="' . $row->username . '" class="delete btn btn-icon btn-sm btn-danger"><i class="mdi mdi-trash-can-outline"></i></button>';
                        }else {
                            $btn .= '<button type="button" data-username="' . $row->username . '" class="restore btn btn-icon btn-sm btn-warning"><i class="mdi mdi-restore-alert"></i></button> ';
                            $btn .= '<button type="button" data-username="' . $row->username . '" class="force-delete btn btn-sm btn-danger"><i class="mdi mdi-trash-can-outline me-1"></i>Hapus Permanen</button>';
                        }

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

    /**
     * @throws Throwable
     */
    public function store(StudentRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->email_verified_at = now();
            $user->save();

            $user->assignRole('student');

            $student = new Student();
            $student->user_id = $user->id;
            $student->whatsapp_number = $request->input('whatsapp_number');
            $student->gender = $request->input('gender');
            $student->save();

            $studentLevel = new StudentLevel();
            $studentLevel->student_id = $student->id;
            $studentLevel->school_year_id = SchoolYear::active()->first()->id;
            $studentLevel->class_level_id = $request->input('class_level_id');
            $studentLevel->sub_class_level_id = $request->input('sub_class_level_id');
            $studentLevel->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function show(User $user)
    {
        Gate::authorize('student', $user);

        return $user;
    }

    public function update(StudentRequest $request, StudentLevel $student)
    {
        $student->update($request->validated());

        return $student;
    }

    public function destroy(StudentLevel $student)
    {
        $student->delete();

        return response()->json();
    }
}
