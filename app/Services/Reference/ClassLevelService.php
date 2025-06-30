<?php

namespace App\Services\Reference;

use App\Models\ClassLevel;
use App\Models\SubClassLevel;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ClassLevelService
{
    use ApiResponse;

    protected ClassLevel $classLevel;
    protected SubClassLevel $subClassLevel;

    /**
     * @param ClassLevel $classLevel
     * @param SubClassLevel $subClassLevel
     */
    public function __construct(ClassLevel $classLevel, SubClassLevel $subClassLevel)
    {
        $this->classLevel = $classLevel;
        $this->subClassLevel = $subClassLevel;
    }

    public function selectClassLevel(Request $request): JsonResponse
    {
        $search = $request->input('search') ?? null;

        try {
            $classLevels = $this->classLevel
                ->when($search, fn($query) => $query->whereLike('name', '%' . $search . '%'))
                ->active()
                ->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $classLevels->map(function (ClassLevel $classLevel) {
            $nextClassLevel = $this->classLevel->oneNextLevel($classLevel->id)
                ->first();

            return collect([
                'id' => $classLevel->id,
                'name' => $classLevel->name,
                'nextLevel' => $nextClassLevel?->name
            ]);
        }), null, Response::HTTP_OK);
    }

    public function selectSubClassLevel(Request $request): JsonResponse
    {
        $search = $request->input('search') ?? null;

        try {
            $subClassLevels = $this->subClassLevel
                ->when($search, fn($query) => $query->whereLike('name', '%' . $search . '%'))
                ->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $subClassLevels->map(function (SubClassLevel $subClassLevel) {
            return collect([
                'id' => $subClassLevel->id,
                'name' => $subClassLevel->name
            ]);
        }), null, Response::HTTP_OK);
    }
}
