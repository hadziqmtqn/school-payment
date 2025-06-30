<?php

namespace App\Http\Controllers\Dashboard\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\PromotedToNextGrade\DatatableRequest;
use App\Http\Requests\Student\PromotedToNextGrade\PromotedRequest;
use App\Models\ClassLevel;
use App\Models\Student;
use App\Models\StudentLevel;
use App\Models\SubClassLevel;
use App\Models\User;
use App\Services\Reference\SchoolYearService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class PromotedToNextGradeController extends Controller implements HasMiddleware
{
    use ApiResponse;

    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('student-write'))
        ];
    }

    protected SchoolYearService $schoolYearService;

    /**
     * @param SchoolYearService $schoolYearService
     */
    public function __construct(SchoolYearService $schoolYearService)
    {
        $this->schoolYearService = $schoolYearService;
    }

    public function index(): View
    {
        $title = 'Naik Kelas';
        $nextSchoolYear = $this->schoolYearService->nextYear();

        return \view('dashboard.student.promoted-to-next-grade', compact('title', 'nextSchoolYear'));
    }

    public function datatable(DatatableRequest $request): JsonResponse
    {
        try {
            $currentSchoolYear = $this->schoolYearService->active();
            $nextSchoolYear = $this->schoolYearService->nextYear();
            $currentClassLevel = ClassLevel::find($request->input('current_class_level'));
            $nextClassLevel = ClassLevel::oneNextLevel($currentClassLevel?->id)
                ->first();

            $currentLevel = $request->input('current_level');
            $nextLevel = $request->input('next_level');

            if ($request->ajax()) {
                $data = User::query()
                    ->with([
                        'student.studentLevel' => function ($query) use ($currentLevel, $nextLevel, $currentSchoolYear, $nextSchoolYear, $currentClassLevel, $nextClassLevel) {
                            $query->promotedToNextGrade([
                                'currentLevel' => $currentLevel,
                                'nextLevel' => $nextLevel,
                                'currentClassLevel' => $currentClassLevel,
                                'nextClassLevel' => $nextClassLevel,
                                'currentSchoolYear' => $currentSchoolYear,
                                'nextSchoolYear' => $nextSchoolYear
                            ]);
                        },
                        'student:id,user_id',
                        'student.studentLevel:id,student_id,class_level_id,sub_class_level_id,is_graduate',
                        'student.studentLevel.classLevel:id,name',
                        'student.studentLevel.subClassLevel:id,name'
                    ])
                    ->whereHas('student.studentLevel', function ($query) use ($currentLevel, $nextLevel, $currentSchoolYear, $nextSchoolYear, $currentClassLevel, $nextClassLevel) {
                        $query->promotedToNextGrade([
                            'currentLevel' => $currentLevel,
                            'nextLevel' => $nextLevel,
                            'currentClassLevel' => $currentClassLevel,
                            'nextClassLevel' => $nextClassLevel,
                            'currentSchoolYear' => $currentSchoolYear,
                            'nextSchoolYear' => $nextSchoolYear
                        ]);
                    })
                    ->active()
                    ->orderBy('name');

                return DataTables::eloquent($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        $search = $request->get('search');

                        $instance->when($search, function ($query) use ($search) {
                            $query->whereAny(['name', 'email'], 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->addColumn('name', function ($row) {
                        $element = '<input type="hidden" name="user_id['. $row->id .']" id="userId-'. $row->id .'" value="'. $row->id .'"> ';
                        $element .= $row->name;

                        return $element;
                    })
                    ->addColumn('classLevel', fn($row) => !$row->student?->studentLevel?->is_graduate ? $row->student?->studentLevel?->classLevel?->name . ' ' . $row->student?->studentLevel?->subClassLevel?->name : 'Lulus')
                    ->addColumn('subClassLevel', function ($row) {
                        $subClassLevels = SubClassLevel::all()
                            ->pluck('name', 'id');

                        // Build options string
                        $options = '';
                        foreach ($subClassLevels as $id => $name) {
                            $options .= '<option value="'. $id .'" '. ($row->student?->studentLevel?->sub_class_level_id == $id ? 'selected' : null) .'>'. $name .'</option>';
                        }

                        return '<div class="form-floating form-floating-outline">
                            <select class="form-select" name="next_sub_class_level['. $row->id .']" id="nextSubClassLevel-'. $row->id .'" aria-label="subClassLevel">'. $options .'</select>
                          </div>';
                    })
                    ->addColumn('promoted', function ($row) {
                        return '<div class="form-check">
                            <input class="form-check-input" name="promoted['. $row->id .']" type="checkbox" id="promoted-'. $row->id .'" value="" checked="">
                          </div>';
                    })
                    ->rawColumns(['name', 'promoted', 'subClassLevel'])
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
    public function store(PromotedRequest $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        dd($request->all());
        try {
            $nextSchoolYear = $this->schoolYearService->nextYear();

            if (!$nextSchoolYear['id']) return $this->apiResponse('Tahun ajaran selanjutnya tidak ditemukan', null, null, Response::HTTP_BAD_REQUEST);

            DB::beginTransaction();

            $students = Student::with([
                'studentLevel' => function ($query) {
                    $query->whereHas('schoolYear', fn($query) => $query->active());
                }
            ])
                ->whereIn('user_id', $request->input('user_id', []))
                ->get();

            $nextSubClassLevels = $request->input('next_sub_class_level', []);
            $promoted = $request->input('promoted', []);

            foreach ($students as $student) {
                $nextClassLevel = ClassLevel::oneNextLevel($student->studentLevel?->class_level_id)
                    ->first();

                if ($nextClassLevel) {
                    if ($promoted[$student->id] == 'yes') {
                        $studentLevel = StudentLevel::filterData([
                            'class_level_id'
                        ])
                            ->studentId($student->id)
                            ->schoolYearId($nextSchoolYear['id'])
                            ->lockForUpdate()
                            ->firstOrNew();
                        $studentLevel->student_id = $student->id;
                        $studentLevel->school_year_id = $nextSchoolYear['id'];
                        $studentLevel->class_level_id = $nextClassLevel->id;
                        $studentLevel->sub_class_level_id = $nextSubClassLevels[$student->id] ?? null;
                    }else {
                        $studentLevel = $student->studentLevel;
                        $studentLevel->is_active = false;
                    }
                }else {
                    $studentLevel = $student->studentLevel;
                    $studentLevel->is_active = false;
                    $studentLevel->is_graduate = true;
                }

                $studentLevel->save();
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal disimpan!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil disimpan!', null, null, Response::HTTP_OK);
    }
}
