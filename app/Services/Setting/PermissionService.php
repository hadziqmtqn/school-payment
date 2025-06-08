<?php

namespace App\Services\Setting;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PermissionService
{
    use ApiResponse;

    protected Permission $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function getPermissions($request): JsonResponse
    {
        try {
            $permissions = $this->permission
                ->when($request['search'] ?? null, function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request['search'] . '%');
                })
                ->get();
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal Server Error', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $permissions->map(function (Permission $permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
            ];
        }), null, Response::HTTP_OK);
    }
}
