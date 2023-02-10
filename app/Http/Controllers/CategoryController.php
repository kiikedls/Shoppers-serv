<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return Category::all();
    }

    public function store(Request $req){
        try{
            $category=new Category();
            $category->user_id=$req->usr;
            $category->name=$req->name;
            //$category->

            if($category->save()){
                return response()->json(['status'=>'success','message'=>'categoria creada exitosamente']);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }
}
