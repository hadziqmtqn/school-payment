<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\MenuRequest;
use App\Models\Menu;
use App\Services\Setting\MenuService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class MenuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('menu-read'), only: ['index']),
            new Middleware(PermissionMiddleware::using('menu-write'), only: ['store', 'update']),
            new Middleware(PermissionMiddleware::using('menu-delete'), only: ['destroy']),
        ];
    }

    protected MenuService $menuService;

    /**
     * @param MenuService $menuService
     */
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        return Menu::all();
    }

    public function store(MenuRequest $request)
    {
        return Menu::create($request->validated());
    }

    public function show(Menu $menu)
    {
        return $menu;
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());

        return $menu;
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json();
    }

    public function select(Request $request)
    {
        return $this->menuService->getMainMenu($request);
    }

    public function searchMenu()
    {
        return $this->menuService->searchMenus();
    }
}
