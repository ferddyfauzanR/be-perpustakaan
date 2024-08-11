<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('check.admin')->only('store', 'update', 'destroy');
        $this->middleware('auth:api')->only('store', 'update', 'destroy');
    }
    public function index()
    {
        $Category = Category::all();
        return response()->json([
            "Message"=>"Tammpilkan Semua Category",
            "data"=> $Category,
        ],200);
    }


    public function store(CategoryRequest $request)
    {
        $Category = Category::create($request->all());
        

        return response()->json([
            "message"=>"Input data Category Berhasil",
            'data'=>$Category
        ], 201);
    }


    public function show(string $id)
    {
        $Category = Category::with('list_books')->find($id);

        if(!$Category){
            return response()->json([
                "message"=>"Data Category dengan ID tersebut tidak di temukan"
            ], 404);
        }
        return response()->json([
            "message"=>"Data Category dengan ID : $id",
            "data"=>$Category,
        ], 201);
    }

   
    public function update(CategoryRequest $request, string $id)
    {
        $Category = Category::find($id);
        if(!$Category){
            return response()->json([
                "message"=>"Data Category dengan ID tersebut tidak di temukan"
            ], 404);
        }
        $Category->name = $request['name'];
        $Category->save();

        return response()->json([
            "message"=>"Update Data Category dengan ID : $id Berhasil",
            "data"=>$Category,
        ], 201);
    }

  
    public function destroy(string $id)
    {
        $Category = Category::find($id);
        if(!$Category){
            return response()->json([
                "message"=>"Data Category dengan ID tersebut tidak di temukan"
            ], 404);
        }
        $Category->delete();
        return response()->json([
            "message"=>"Delete Data Category dengan ID : $id Berhasil",
            "data"=>$Category,
        ], 201);
    }
}
