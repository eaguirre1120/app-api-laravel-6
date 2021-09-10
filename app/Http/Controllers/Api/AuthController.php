<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // var_dump($request->all());
        // exit;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ["error" => $validator->errors()],
                422
            );
        }

        $input = $request->all();

        $input['password'] = bcrypt($request->get('password'));

        $user = User::create($input);
        $token = $user->createToken('MyApp')->accessToken;

        return response()->json(
            [
                'token' => $token,
                'user' => $user
            ],
            200
        );
    }
}
