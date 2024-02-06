<?php

namespace App\Http\Controllers;

use App\Notifications\LoginNeedsVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{

    public function submit(Request $request): JsonResponse
    {
//        validate phone number

        $request->validate([
            'phone'=>'required|numeric|min:10'
        ]);

//        find or create a user model
        $user = User::firstOrCreate([
                'phone'=>$request->phone
        ]);
        if(!$user){
            return response()->json([
                'status'=>501,
                ['message'=>'Could not process user with that phone number']
            ]);
        }
//        send the user OTP
        $user->notify(new LoginNeedsVerification());

//        return back a response
        return response()->json([
           'message'=> "Text message notification sent."
        ]);
    }
    public  function verify(Request $request){
//        validate the incoming request
        $request->validate([
            'phone'=>'required|numeric|min:10',
            'login_code'=>'required|numeric|between:111111,999999'
        ]);
//        find the user
        $user = User::where('phone',$request->phone)
            ->where('login_code',$request->login_code)
            ->first();
//        is the code provided the same one saved ? or it is expired
//        if so, return back auth token
        if($user){
            $user->update([
                'login_code'=>null,
            ]);
            return response()->json([
                'auth_token'=>$user->createToken('Auth_token')->plainTextToken
            ]);
        }
//        if not , return back a message
        return  response()->json([
            'status'=>401,
            'message'=>'Invalid Verification code.'

        ]);

    }
}
