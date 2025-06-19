<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhatsappApiConfig\WhatsappApiConfigRequest;
use App\Models\WhatsappApiConfig;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Middleware\PermissionMiddleware;

class WhatsappApiConfigController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('whatsapp-api-config-read'), only: ['index']),
            new Middleware(PermissionMiddleware::using('whatsapp-api-config-write'), only: ['store'])
        ];
    }

    public function index(): View
    {
        $title = 'Whatsapp API';
        $whatsappApiConfig = WhatsappApiConfig::firstOrFail();

        return \view('dashboard.setting.whatsapp-api-config.index', compact('title', 'whatsappApiConfig'));
    }

    public function store(WhatsappApiConfigRequest $request): RedirectResponse
    {
        try {
            $whatsappApiConfig = WhatsappApiConfig::firstOrNew();
            $whatsappApiConfig->endpoint = $request->input('endpoint');
            $whatsappApiConfig->api_key = $request->input('api_key');
            $whatsappApiConfig->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }
}
