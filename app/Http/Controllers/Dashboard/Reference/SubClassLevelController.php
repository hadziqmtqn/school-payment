<?php

namespace App\Http\Controllers\Dashboard\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassLevel\SubClassLevelRequest;
use App\Models\SubClassLevel;

class SubClassLevelController extends Controller
{
    public function index()
    {
        return SubClassLevel::all();
    }

    public function store(SubClassLevelRequest $request)
    {
        return SubClassLevel::create($request->validated());
    }

    public function show(SubClassLevel $subClassLevel)
    {
        return $subClassLevel;
    }

    public function update(SubClassLevelRequest $request, SubClassLevel $subClassLevel)
    {
        $subClassLevel->update($request->validated());

        return $subClassLevel;
    }

    public function destroy(SubClassLevel $subClassLevel)
    {
        $subClassLevel->delete();

        return response()->json();
    }
}
