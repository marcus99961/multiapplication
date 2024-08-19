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


    public function index2(Request $request)
    {
        
        $category = \DB::table('departmentcategories')
        ->join('categories','departmentcategories.category_id','categories.id')
         ->where('departmentcategories.department_id',Department::where('name',$request->keyword)->first()->id)
        ->select('departmentcategories.*','categories.name')
        ->pluck('categories.name')->toArray();
        //Departmentcategory::pluck('name')->toArray();
        return response()->json($category);
       
       
    }


    public function selectedcategory()
    {
        $selected = \DB::table('complaintrooms')
        ->join('rooms','complaintrooms.room_id','rooms.id')
        ->select('rooms.name','complaintrooms.*')
        ->get();
        return response()->json($selected);
    }


    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:categories|min:2',
            // 'currency' => 'required',
            // 'payment_type' => 'required',

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
        $room = new Category();
        $room->name = $request->name;


        $room->save();
        return response()->json('Success');
    }
    public function update(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:categories',
            // 'currency' => 'required',
            // 'payment_type' => 'required',

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
       Category::where('id',$request->id)->update([
        'name'=>$request->name,
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


    public function Link(request $request,$id)
    {
        

       
           $verify =  \DB::table('departmentcategories')
           ->where('department_id',$id)
           ->where('category_id',$request->id)
           ->first();
           if(!$verify){
        $categorylink = new Departmentcategory();
        $categorylink->department_id = $id;
        $categorylink->category_id = $request->id;
        $categorylink->save();

        return response()->json('Success');
    }
    }
   
    public function unlink($id)
    {

        //dd($id);
        \DB::table('departmentcategories')
        ->where('id',$id)        
        ->delete();



        return response()->json('success');
    }
}
