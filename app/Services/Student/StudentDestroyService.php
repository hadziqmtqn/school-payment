<?php

namespace App\Services\Student;

use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StudentDestroyService
{
    use ApiResponse;

    /**
     * @throws Throwable
     */
    public function destroy(User $user): JsonResponse
    {
        Gate::authorize('student-destroy', $user);

        try {
            DB::beginTransaction();
            $user->is_active = false;
            $user->save();

            $user->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal dihapus!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil dihapus!', null, null, Response::HTTP_OK);
    }

    public function restore($username): JsonResponse
    {
        try {
            $user = User::onlyTrashed()
                ->filterByUsername($username)
                ->firstOrFail();

            $user->restore();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal dikembalikan!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil dikembalikan!', null, null, Response::HTTP_OK);
    }

    public function permanentlyDelete($username): JsonResponse
    {
        try {
            $user = User::onlyTrashed()
                ->filterByUsername($username)
                ->firstOrFail();

            $user->forceDelete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Data gagal dihapus permanen!', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Data berhasil dihapus permanen!', null, null, Response::HTTP_OK);
    }
}
