<?php

namespace App\Http\ViewComposers;

use App\Services\Setting\ApplicationService;
use App\Services\Setting\MenuService;
use Illuminate\View\View;

class AppViewComposer
{
    protected ApplicationService $applicationService;
    protected MenuService $menuService;

    /**
     * @param ApplicationService $applicationService
     * @param MenuService $menuService
     */
    public function __construct(ApplicationService $applicationService, MenuService $menuService)
    {
        $this->applicationService = $applicationService;
        $this->menuService = $menuService;
    }

    public function compose(View $view): void
    {
        $view->with('application', $this->applicationService->getApp());
        if (auth()->check()) $view->with('listMenus', $this->menuService->getMenus());
    }
}
