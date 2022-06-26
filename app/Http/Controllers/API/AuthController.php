<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//using User model
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    //Login method to validate auth/login input credential
    public function login(Request $request){

    //Implement try catch
        try{

            //using validator for request fields validations
            $validator = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validator->fails()){

                $error = $validator->errors()->all()[0];
                return response()->json([
                    'success' => false,
                    'message' => $error,
                    'error_code' => 422
                ],422);

            }else{

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


        } catch (\Exception $e){
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => 500
                ], 500
            );
        }
      

    }
}
