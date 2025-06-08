<?php

namespace App\Services\Setting;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ReflectionClass;
use ReflectionException;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RoleService
{
    use ApiResponse;

    protected Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function select($request): JsonResponse
    {
        try {
            $roles = $this->role->query()
                ->when($request['search'], function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request['search'] . '%');
                })
                ->where('name', '!=', 'user')
                ->get();
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $roles->map(function (Role $role) {
            return collect([
                'slug' => $role->slug,
                'name' => ucfirst(str_replace('-', ' ', $role->name)),
            ]);
        }), null, Response::HTTP_OK);
    }

    /**
     * @throws ReflectionException
     */
    public function getAllModelsFromClassmap(): array
    {
        // Path ke folder Models
        $modelsPath = app_path('Models');

        // Muat semua file di direktori Models
        foreach (File::allFiles($modelsPath) as $file) {
            $class = 'App\\Models\\' . str_replace('.php', '', $file->getFilename());

            // Pastikan class ada sebelum ditambahkan
            if (class_exists($class)) {
                continue; // Class sudah ada
            }

            require_once $file->getPathname(); // Muat file PHP
        }

        // Ambil semua kelas yang telah dideklarasikan
        $models = [];
        $classes = get_declared_classes();

        foreach ($classes as $class) {
            if (is_subclass_of($class, Model::class) && !(new ReflectionClass($class))->isAbstract() && str_starts_with($class, 'App\Models\\')) {
                $models[] = $class;
            }
        }

        return $models;
    }
}
