<?php

namespace App\Http\Controllers\Dashboard\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\PromotedToNextGrade\DatatableRequest;
use App\Models\ClassLevel;
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

        return \view('dashboard.student.promoted-to-next-grade', compact('title'));
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
                        'student.studentLevel.classLevel',
                        'student.studentLevel.subClassLevel'
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
                    ->when($request->get('is_active'), function ($query) use ($request) {
                        $query->when($request->get('is_active') != 'deleted', function ($query) use ($request) {
                            $query->where('is_active', ($request->get('is_active') == 'active'));
                        });

                        $query->when($request->get('is_active') == 'deleted', function ($query) use ($request) {
                            $query->onlyTrashed();
                        });
                    })
                    ->orderBy('name');

                return DataTables::eloquent($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        $search = $request->get('search');

                        $instance->when($search, function ($query) use ($search) {
                            $query->whereAny(['name', 'email'], 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->addColumn('regNumber', fn($row) => $row->student?->reg_number)
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
}
