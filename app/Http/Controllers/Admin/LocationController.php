<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::orderBy('name')  
        ->get();
        return response()->json($locations);
    }
    public function index2(Request $request)
    {
        $locations = Location::find($request->keyword);
        return response()->json($locations);
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
           
        $location = new Location();
        $location->name = $request->name;
    


        $location->save();
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
           
       Location::where('id',$request->id)->update([
        'name'=>$request->name,
      
       ]);
       

  
        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return response()->noContent();
    }
}
