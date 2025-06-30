<?php

namespace App\Http\Controllers\Dashboard\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\PromotedToNextGrade\DatatableRequest;
use App\Models\ClassLevel;
use App\Models\SubClassLevel;
use App\Models\User;
use App\Services\Reference\SchoolYearService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PromotedToNextGradeController extends Controller
{
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
                        /*'student.studentLevel' => function ($query) use ($request) {
                            $query->schoolYearId($request->input('school_year_id'));
                        },*/
                        'student:id,user_id',
                        'student.studentLevel:id,student_id,class_level_id,sub_class_level_id,is_graduate',
                        'student.studentLevel.classLevel:id,name',
                        'student.studentLevel.subClassLevel:id,name'
                    ])
                    ->whereHas('student.studentLevel', function ($query) use ($currentLevel, $nextLevel, $currentSchoolYear, $nextSchoolYear, $currentClassLevel, $nextClassLevel) {
                        /**
                         * Jika level saat ini yang diambil, data yang diambil berdasarkan tahun ajaran aktif dan level kelas yang dipilih
                        */
                        $query->when($currentLevel == 'yes', function ($query) use ($currentSchoolYear, $currentClassLevel) {
                            $query->schoolYearId($currentSchoolYear['id'])
                                ->classLevelId($currentClassLevel?->id);
                        });

                        /**
                         * Jika setelah level saat ini yang diambil, data yang diambil tahun ajaran berikutnya dan level kelas 1 tingkat selanjutnya (bukan tingkat tertinggi) dari level kelas yang dipilih
                        */
                        $query->when($nextLevel == 'yes' && !$nextClassLevel?->isMaxSerialNumber(), function ($query) use ($nextSchoolYear, $nextClassLevel) {
                            $query->schoolYearId($nextSchoolYear['id'])
                                ->classLevelId($nextClassLevel?->id);
                        });

                        /**
                         * Jika level berikutnya yang diambil dan level kelas tertinggi, ambil data berdasarkan tahun ajaran berikutnya dan telah lulus
                        */
                        $query->when($nextLevel == 'yes' && $nextClassLevel?->isMaxSerialNumber(), function ($query) use ($nextSchoolYear) {
                            $query->schoolYearId($nextSchoolYear['id'])
                                ->graduate();
                        });
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
                    ->rawColumns(['promoted', 'subClassLevel'])
                    ->make();
            }
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return response()->json(true);
    }
}
