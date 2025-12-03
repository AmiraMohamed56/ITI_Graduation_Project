<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function edit () {
        // dd(session()->all());
        $admin = Auth::user();
        return view('admin.settings.edit', compact('admin'));
    }

    public function update (Request $request) {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|confirmed|min:6',
            'profile_pic' => 'nullable|image',
        ]);
        // dd($request->all());

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }



        if ($request->hasFile('profile_pic')) {
            $fileName = time() . '_' . $request->profile_pic->getClientOriginalName();
            $request->profile_pic->storeAs('/profile_pics', $fileName, 'public');
            $admin->profile_pic = $fileName;
        }

        $admin->save();

        return redirect()->route('admin.settings.edit')->with('success', 'Profile updated successfully');


    }
}
