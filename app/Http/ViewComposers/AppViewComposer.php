<?php

namespace App\Http\ViewComposers;

use App\Services\Reference\SchoolYearService;
use App\Services\Setting\ApplicationService;
use App\Services\Setting\MenuService;
use Illuminate\View\View;

class AppViewComposer
{
    protected ApplicationService $applicationService;
    protected MenuService $menuService;
    protected SchoolYearService $schoolYearService;

    /**
     * @param ApplicationService $applicationService
     * @param MenuService $menuService
     * @param SchoolYearService $schoolYearService
     */
    public function __construct(ApplicationService $applicationService, MenuService $menuService, SchoolYearService $schoolYearService)
    {
        $this->applicationService = $applicationService;
        $this->menuService = $menuService;
        $this->schoolYearService = $schoolYearService;
    }

    public function compose(View $view): void
    {
        $view->with('application', $this->applicationService->getApp());
        $view->with('schoolYearActive', $this->schoolYearService->active());

        if (auth()->check()) $view->with('listMenus', $this->menuService->getMenus());
    }
}
