<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageTemplate\MessageTemplateRequest;
use App\Models\MessageTemplate;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Middleware\PermissionMiddleware;

class MessageTemplateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('message-template-read'), only: ['index']),
            new Middleware(PermissionMiddleware::using('message-template-write'), only: ['store', 'update']),
            new Middleware(PermissionMiddleware::using('message-template-delete'), only: ['destroy']),
        ];
    }

    private function categories()
    {
        return ['registrasi-akun-baru', 'tagihan-bulanan'];
    }

    public function index(): View
    {
        $title = 'Template Pesan';
        $messageTemplates = MessageTemplate::get();
        $categories = $this->categories();

        return \view('dashboard.setting.message-template.index', compact('title', 'messageTemplates', 'categories'));
    }

    public function store(MessageTemplateRequest $request): RedirectResponse
    {
        try {
            $messageTemplate = new MessageTemplate();
            $messageTemplate->category = $request->input('category');
            $messageTemplate->recipient = $request->input('recipient');
            $messageTemplate->title = $request->input('title');
            $messageTemplate->message = $request->input('message');
            $messageTemplate->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function show(MessageTemplate $messageTemplate): View
    {
        $title = 'Template Pesan';
        $subTitle = 'Detail Template Pesan';
        $messageTemplates = MessageTemplate::get();
        $categories = $this->categories();

        return \view('dashboard.setting.message-template.show', compact('title', 'subTitle', 'messageTemplates', 'messageTemplate', 'categories'));
    }

    public function update(MessageTemplateRequest $request, MessageTemplate $messageTemplate): RedirectResponse
    {
        try {
            $messageTemplate->category = $request->input('category');
            $messageTemplate->recipient = $request->input('recipient');
            $messageTemplate->title = $request->input('title');
            $messageTemplate->message = $request->input('message');
            $messageTemplate->save();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function destroy(MessageTemplate $messageTemplate): RedirectResponse
    {
        try {
            $messageTemplate->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return to_route('message-template.index')->with('success', 'Data berhasil dihapus!');
    }
}
