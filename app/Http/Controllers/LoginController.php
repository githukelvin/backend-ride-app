<?php

namespace App\Http\Controllers;

use http\Client\Curl\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function submit(Request $request){
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
        $user->notify();

//        return back a response
    }
}
