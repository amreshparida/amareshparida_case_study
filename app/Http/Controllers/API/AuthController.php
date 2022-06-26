<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//using User model
use App\Models\User;


class AuthController extends Controller
{
    //Login method to validate auth/login input credential
    public function login(Request $request){
       $data = [ 'email' => $request->email, 'password' => $request->password ];

       if(auth()->attempt($data))
       {
            //generating token for user
            $token = auth()->user()->createToken('Token')->accessToken;

            //Return JSON logged in success response with access token
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'data' =>   [
                                'token' => $token
                            ]
                ],);
       }
       else{
        // return log in failled JSON response
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
            'error_code' => 401
            ], 401);
       }

    }
}
