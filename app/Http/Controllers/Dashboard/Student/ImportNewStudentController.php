<?php

namespace App\Http\Controllers\Dashboard\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ImportRequest;
use App\Imports\StudentImport;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ImportNewStudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('student-write'), only: ['store'])
        ];
    }

    public function store(ImportRequest $request): RedirectResponse
    {
        try {
            Excel::import(new StudentImport(), $request->file('file')->getRealPath(), null, \Maatwebsite\Excel\Excel::XLSX);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->back()->with('success', 'Data sedang diproses di latar belakang');
    }
}
