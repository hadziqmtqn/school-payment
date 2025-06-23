<?php

namespace App\Services\Reference;

use App\Models\SchoolYear;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SchoolYearService
{
    use ApiResponse;

    protected SchoolYear $schoolYear;

    /**
     * @param SchoolYear $schoolYear
     */
    public function __construct(SchoolYear $schoolYear)
    {
        $this->schoolYear = $schoolYear;
    }

    public function select(): JsonResponse
    {
        try {
            $schoolYears = $this->schoolYear->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $schoolYears->map(function (SchoolYear $schoolYear) {
            return collect([
                'id' => $schoolYear->id,
                'year' => $schoolYear->year
            ]);
        }), null, Response::HTTP_OK);
    }

    public function active(): Collection
    {
        $schoolYear = $this->schoolYear->active()
            ->first();

        return collect([
            'id' => $schoolYear?->id,
            'year' => $schoolYear?->year
        ]);
    }
}
