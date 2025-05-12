<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\Admin;

class AdminController extends Controller
{
    public function index()
    {
        return Admin::all();
    }

    public function store(AdminRequest $request)
    {
        return Admin::create($request->validated());
    }

    public function show(Admin $admin)
    {
        return $admin;
    }

    public function update(AdminRequest $request, Admin $admin)
    {
        $admin->update($request->validated());

        return $admin;
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json();
    }
}
