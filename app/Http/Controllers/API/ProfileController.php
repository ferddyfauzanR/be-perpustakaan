<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'required|string',
            'age' => 'required|integer',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentUser = Auth::guard('api')->user();


        $profileData = Profile::updateOrCreate(  
            ['user_id' => $currentUser->id] ,
                [
                    'bio' => $request['bio'],
                    'age' => $request['age'],
                    'user_id' => $currentUser->id,

                ]
        );

        return response()->json([
            'Message' => "Create/Update Profile berhasil",
            'data' => $profileData
        ], 201);
    }
}
