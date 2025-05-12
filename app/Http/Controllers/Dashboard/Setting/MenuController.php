<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\MenuRequest;
use App\Models\Menu;

class MenuController extends Controller
{
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
}
