<?php

namespace App\Services\Setting;

use App\Models\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ApplicationService
{
    protected Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function getApp(): Collection
    {
        $application = $this->application
            ->firstOrFail();

        return collect([
            'name' => $application->name,
            'schoolName' => $application->school_name,
            'notificationMethod' => $application->notification_method,
            'logo' => $application->hasMedia('logo') ? $application->getFirstTemporaryUrl(Carbon::now()->addHour(), 'logo') : asset('assets/sekolah.png')
        ]);
    }
}
