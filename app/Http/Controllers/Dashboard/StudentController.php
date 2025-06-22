<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentRequest;
use App\Models\StudentLevel;

class StudentController extends Controller
{
    public function index()
    {
        return StudentLevel::all();
    }

    public function store(StudentRequest $request)
    {
        return StudentLevel::create($request->validated());
    }

    public function show(StudentLevel $student)
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
