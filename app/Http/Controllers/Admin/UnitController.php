<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit = Unit::get();
        return response()->json($unit);
    }
    public function index2()
    {
        $room = Unit::orderBy('name')->pluck('name')->toArray();
        return response()->json($room);
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
            'name' => 'required|unique:units|min:2',
          

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
        $unit = new Unit();
        $unit->name = $request->name;
    


        $unit->save();
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
            'name' => 'required|unique:units,name,'.$id,
        

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
       Unit::where('id',$request->id)->update([
        'name'=>$request->name,
      
       ]);
       

  
        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {

        $unit->delete();

        return response()->noContent();
    }
}
