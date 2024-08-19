<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $room = Room::orderBy('name')->get();
        return response()->json($room);
    }

    public function index2()
    {
        $room = Room::orderBy('name')->pluck('name')->toArray();
        return response()->json($room);
    }

    public function selectedrooms()
    {
        $selected = \DB::table('complaintrooms')
        ->join('rooms','complaintrooms.room_id','rooms.id')
        ->select('rooms.name','complaintrooms.*')
        ->orderBy('rooms.name')
        ->get();
        return response()->json($selected);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required','not_regex:/(0)[1-9][0-9]{2}$/','unique:rooms','min:2'],
            // 'currency' => 'required',
            // 'payment_type' => 'required',

           ],
           [
            'name.required'=>'Please input Room No.',
            'name.not_regex'=>'Do not put 0(zero) infront of room no.',
            'name.min'=>"It's too short",
           ]
        );
           
        $room = new Room;
        $room->name = $request->name;


        $room->save();
        return response()->json('Success');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $validateData = $request->validate([
            'name' => 'required|unique:rooms|not_regex:/(0)[1-9][0-9]{2}$/|min:2',
          

           ],
           [
            'name.required'=>'Please input Room No.',
            'name.not_regex'=>'Do not put 0(zero) infront of room no.',
            'name.min'=>"It's too short",
           ]
        );
           
       Room::where('id',$id)->update(['name'=>$request->name]);
        return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
