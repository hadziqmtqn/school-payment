<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageTemplate\MessageTemplateRequest;
use App\Models\MessageTemplate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
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

    public function index(): View
    {
        $title = 'Template Pesan';
        $messageTemplates = MessageTemplate::get();

        return \view('dashboard.setting.message-template.index', compact('title', 'messageTemplates'));
    }

    public function store(MessageTemplateRequest $request)
    {
        return MessageTemplate::create($request->validated());
    }

    public function show(MessageTemplate $messageTemplate): View
    {
        $title = 'Template Pesan';
        $subTitle = 'Detail Template Pesan';
        $messageTemplates = MessageTemplate::get();

        return \view('dashboard.setting.message-template.show', compact('title', 'subTitle', 'messageTemplates', 'messageTemplate'));
    }

    public function update(MessageTemplateRequest $request, MessageTemplate $messageTemplate)
    {
        $messageTemplate->update($request->validated());

        return $messageTemplate;
    }

    public function destroy(MessageTemplate $messageTemplate)
    {
        $messageTemplate->delete();

        return response()->json();
    }
}
