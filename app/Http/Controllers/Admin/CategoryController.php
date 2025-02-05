<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Departmentcategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
       
        $room = Category::get();
        return response()->json($room);
    }

    public function index2()
    {
        $room = Category::orderBy('name')->pluck('name')->toArray();
        return response()->json($room);
    }
    // public function index2(Request $request)
    // {
        
    //     $category = \DB::table('departmentcategories')
    //     ->join('categories','departmentcategories.category_id','categories.id')
    //      ->where('departmentcategories.department_id',Department::where('name',$request->keyword)->first()->id)
    //     ->select('departmentcategories.*','categories.name')
    //     ->pluck('categories.name')->toArray();
    //     //Departmentcategory::pluck('name')->toArray();
    //     return response()->json($category);
       
       
    // }




    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:categories|min:2',
            'group_code' => 'required|unique:categories',
            'inv_code' => 'required',

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
        $category = new Category();
        $category->name = $request->name;
        $category->group_code = $request->group_code;
        $category->inv_code = $request->inv_code;


        $category->save();
        return response()->json('Success');
    }
    public function update($id,Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:categories,name,'.$id,
            'group_code' => 'required|unique:categories,group_code,'.$id,
            'inv_code' => '',


           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
       Category::where('id',$request->id)->update([
        'name'=>$request->name,
        'group_code'=> $request->group_code,
        'inv_code' => $request->inv_code,
       ]);
       

  
        return response()->json('Success');
    }


    public function show($id)
    {

        $categories = \DB::table('departmentcategories')
        ->join('categories','departmentcategories.category_id','categories.id')
        ->select('categories.name','departmentcategories.id')
        ->where('departmentcategories.department_id',$id)
        ->get();

        return response()->json($categories);
    }


    public function destroy(Category $category)
    {

        $category->delete();

        return response()->noContent();
    }
    
   
  
}
