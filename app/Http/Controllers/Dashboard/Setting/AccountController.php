<?php

namespace App\Http\Controllers\Dashboard\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $title = 'Akun Saya';

        return \view('dashboard.account.index', compact('title'));
    }

    public function store(AccountRequest $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $request->input('password') ? Hash::make($request->input('password')) : $user->password;
            $user->save();

            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                if ($user->hasMedia('photo')) $user->clearMediaCollection('photo');

                $user->addMediaFromRequest('photo')
                    ->toMediaCollection('photo');
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }
}
