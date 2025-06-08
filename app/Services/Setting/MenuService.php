<?php

namespace App\Services\Setting;

use App\Models\Menu;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MenuService
{
    use ApiResponse;

    protected Menu $menu;

    /**
     * @param Menu $menu
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function getMainMenu($request): JsonResponse
    {
        try {
            $menus = $this->menu
                ->search($request)
                ->filterByType('main_menu')
                ->get();
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Success', $menus->map(function (Menu $menu) {
            return [
                'id' => $menu->id,
                'name' => $menu->name,
            ];
        }), null, Response::HTTP_OK);
    }

    public function getMenus(): Collection
    {
        return $this->menu
            ->filterByType('main_menu')
            ->orderBy('serial_number')
            ->get()
            ->map(function (Menu $menu) {
                if (!auth()->user()->hasAnyPermission($menu->visibility)) {
                    return null; // Skip this menu if user doesn't have permission
                }

                $subMenus = $this->menu->filterByType('sub_menu')
                    ->mainMenu($menu->id)
                    ->orderBy('serial_number')
                    ->get()
                    ->map(function (Menu $subMenu) {
                        if (!auth()->user()->hasAnyPermission($subMenu->visibility)) {
                            return null; // Skip this sub menu if user doesn't have permission
                        }
                        return collect([
                            'name' => $subMenu->name,
                            'type' => $subMenu->type,
                            'icon' => $subMenu->icon,
                            'url' => url($subMenu->url),
                            'visibility' => $subMenu->visibility,
                        ]);
                    })->filter();

                return collect([
                    'name' => $menu->name,
                    'type' => $menu->type,
                    'icon' => $menu->icon,
                    'url' => url($menu->url),
                    'visibility' => $menu->visibility,
                    'subMenus' => $subMenus,
                ]);
            })->filter();
    }

    public function searchMenus(): Collection
    {
        return collect([
            'pages' => $this->menu
                ->where('url', '!=', '#')
                ->get()
                ->map(function (Menu $menu) {
                    return [
                        'name' => $menu->name,
                        'icon' => 'mdi-' . $menu->icon,
                        'url' => url($menu->url),
                    ];
                })->all()
        ]);
    }
}
