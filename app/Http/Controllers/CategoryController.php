<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
        try{
            $validate = $request->validate([
                'name'=>'required'
            ],[
                'name'=>[
                    'required'=>'category name is required'
                ]
            ]);


            $category = new Category();
            $category->name = $request->name;
            $result = $category->save();
            if($result){
                return response()->json(['message'=>'category created !','category'=>$category],201);
            }
            else{
                return response()->json(['error'=>'error occurred'],403);
            }
        }
        catch (\Exception $e){
            return response(['validationError'=>$e->getMessage()]);
        }

    }
}
