<?php

namespace App\Http\ViewComposers;

use App\Services\Setting\ApplicationService;
use Illuminate\View\View;

class AppViewComposer
{
    protected ApplicationService $applicationService;

    /**
     * @param ApplicationService $applicationService
     */
    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function compose(View $view): void
    {
        $view->with('application', $this->applicationService->getApp());
    }
}
