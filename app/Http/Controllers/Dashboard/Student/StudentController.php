<?php

namespace App\Http\Controllers\Dashboard\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentRequest;
use App\Models\Student;
use App\Models\StudentLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Spatie\Permission\Middleware\PermissionMiddleware;

class StudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware(PermissionMiddleware::using('student-read'), only: ['index']),
            new Middleware(PermissionMiddleware::using('student-write'), only: ['store', 'update']),
        ];
    }

    public function index(): View
    {
        $title = 'Siswa';

        return \view('dashboard.student.index', compact('title'));
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        return StudentLevel::create($request->validated());
    }

    public function show(Student $student)
    {
        return $student;
    }

    public function update(StudentRequest $request, StudentLevel $student)
    {
        $student->update($request->validated());

        return $student;
    }

    public function destroy(StudentLevel $student)
    {
        $student->delete();

        return response()->json();
    }
}
