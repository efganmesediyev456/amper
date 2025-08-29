<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(){
        return view('backend.pages.auth.login');
    }

    public function loginPost(Request $request){
        $this->validate($request, [
            "email"=>"required",
            "email"=>"email",
            "password"=>"required"
        ],[
            "email.required" => "Email daxil etmək vacibdir",
            "email.email" => "Email düzgün formatda olmalıdır",
            "password.required"=>"Şifrə daxil etmək vacibdir"
        ]);

        if(Auth::guard("admin")->attempt($request->only("email","password"))){
            // dd(Auth::guard("admin")->user());
            return $this->responseMessage("success", "Uğurla daxil oldunuz", [],200);
        }

        return $this->responseMessage("error", "Email və ya şifrə səhvdir", [],500);
    }

    public function logout(){
        Auth::guard("admin")->logout();
        return redirect()->route('admin.login')->withSuccess('Uğurla çıxış edildi');
    }
}
