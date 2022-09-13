<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function change_password()
    {
        return view('pages.profile.password');
    }

    public function update_password(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'old_password' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',
        ]);


        if(Hash::check($request->password, $user->password)){
            return redirect()->route('change_password.index')->with('error', "Kata Sandi sebelumnya salah.");
        }

        else if($request->password != $request->password_confirmation){
            return redirect()->route('change_password.index')->with('error', "Konfirmasi Kata Sandi salah.");
        }

        else {
            $reset = User::find($user->id);
            $reset->password = Hash::make($request->password);

            if ($reset->save()){
                return redirect()->route('change_password.index')->with('success', "Berhasil mengubah kata sandi.");
            }else {
                return redirect()->route('change_password.index')->with('error', "Terjadi kesalahan ketika mengubah kata sandi.");
            }
        }
    }
}
