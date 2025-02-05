<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
              
        $supplier = Supplier::orderBy('name')->get();
        return response()->json($supplier);
    }

    public function index2()
    {
        $supplier = Supplier::orderBy('name')->pluck('name')->toArray();
        return response()->json($supplier);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name'=> 'required|unique:items,name',
            'address'=> '',
            'email'=>'',
            'phone'=>'',
          

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
        $item = new Supplier();
        $item->name = $request->name;
        $item->address = $request->address;
        $item->email = $request->email;
        $item->phone = $request->phone;
   
       

        $item->save();
        return response()->json('Success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'name'=> 'required|unique:items,name',
            'address'=> '',
            'email'=>'',
            'phone'=>'',
          

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
        Supplier::where('id',$request->id)->update([
            'name'=>$request->name,
            'email'=> $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
         
           ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->noContent();
    }
}
