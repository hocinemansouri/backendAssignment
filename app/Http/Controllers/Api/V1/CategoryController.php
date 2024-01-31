<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:categories|max:64',

        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Validation errors',
                'errors'=> $validator->messages()
            ],422);
        }

        $category = Category::create([
            'name'=>$request->name,
            'is_archived'=>$request->is_archived
        ]);
    
    return response()->json([
            'message'=>'Category succesfully created',
            'data'=> $category
        ],200);
    }
}
