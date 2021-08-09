<?php

namespace App\Http\Controllers\CKG_Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DefaultController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            $user = Auth::user();
            $success['token'] = $user->createToken("CKG_Mobile_Login")->accessToken;

            $user = DB::table('view_users_general_info')
                ->where('id', Auth::user()->id)
                ->first();

            return response()
                ->json(['status' => 1, 'role' => $user->display_name], 200);
        }

        return response()
            ->json(['status' => '0', 'message' => 'Hatalı kullanıcı adı veya şifre!'], 200);
    }

    public function user()
    {
        $user = DB::table('view_users_general_info')
            ->select(['id', 'name_surname', 'email', 'phone', 'created_at', 'display_name', 'agency_name', 'city', 'district', 'tc_city', 'tc_name'])
            ->where('id', Auth::user()->id)
            ->first();

        return response()
            ->json($user, 200);
    }


    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->authUserToken()->delete();

            return response()
                ->json(['status' => 1, 'message' => 'İşlem başarılı, çıkış yapıldı!'], 200);
        }

    }
}
