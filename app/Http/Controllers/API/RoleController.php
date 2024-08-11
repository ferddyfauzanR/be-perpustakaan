<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    
    public function index()
    {
        $user = Auth::guard('api')->user();
        $role = role::all();
        return response()->json([
            "Message"=>"Tammpilkan Semua role",
            "user"=>$user->name,
            "data"=> $role,

        ],200);
    }


    public function store(Request $request)
    {
        $role = role::create($request->all());
        
        $user = Auth::guard('api')->user();
        return response()->json([
            "message"=>"Input data role Berhasil",
            "user"=>$user->name,
            'data'=>$role
        ], 201);
    }

    public function show(string $id)
    {
        $role = role::find($id);
        $user = Auth::guard('api')->user();
        if(!$role){
            return response()->json([
                "message"=>"Data Role dengan ID tersebut tidak di temukan"
            ], 404);
        }
        return response()->json([
            "message"=>"Data Role dengan ID : $id",
            "user"=>$user->name,
            "data"=>$role,
        ], 201);
    }

   
    public function update(Request $request, string $id)
    {
        $role = role::find($id);
        if(!$role){
            return response()->json([
                "message"=>"Data Role dengan ID tersebut tidak di temukan"
            ], 404);
        }
        $user = Auth::guard('api')->user();
        $role->name = $request['name'];
        $role->save();

        return response()->json([
            "message"=>"Update Data role dengan ID : $id Berhasil",
            "user"=>$user->name,
            "data"=>$role,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = role::find($id);
        if(!$role){
            return response()->json([
                "message"=>"Data Role dengan ID tersebut tidak di temukan"
            ], 404);
        }
        $role->delete();
        return response()->json([
            "message"=>"Delete Data role dengan ID : $id Berhasil",
            "data"=>$role,
        ], 201);
    }
}
