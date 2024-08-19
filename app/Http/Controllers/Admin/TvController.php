<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Television;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $items = Television::
            where(function($query) use ($request){
            $query->where('room_no', 'like', '%'.$request->keyword.'%')
            ->orWhere('brand', 'like', '%'.$request->keyword.'%')
            ->orWhere('size', 'like', '%'.$request->keyword.'%')            
            ->orWhere('type', 'like', '%'.$request->keyword.'%');                
            })     
        
        ->orderBy('room_no','asc')->paginate(setting('pagination_limit'));
        return response()->json($items);
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
            'room_no' => 'required|unique:televisions|min:3',
            'brand' => 'required',
            'size' => 'required',
            'model' => 'required',
            'type' => 'required'


           ]);
       
        $item = new Television();
        $item->room_no = $request->room_no;
        $item->brand = $request->brand;
        $item->size = $request->size;
        $item->tuner = $request->tuner;      
        $item->model = $request->model;
        $item->type = $request->type;
        $item->note = $request->note;   
        $item->user_id = Auth::user()->id;
        $item->save();
       
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
    public function update(Request $request)
    {
        $validateData = $request->validate([
            'room_no' => 'required|unique:televisions,room_no,'.$request->id,
            'brand' => 'required',
            'size' => 'required',
            'model' => 'required',
            'type' => 'required'


           ]);
           Television::where('id',$request->id)->update([
            'room_no'=>$request->room_no,
            'brand'=>$request->brand,
            'size'=>$request->size,
            'model'=>$request->model,
            'tuner'=>$request->tuner,
            'type'=>$request->type,
            'note'=>$request->note,
           ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Television $item)
    {
       
        $item->delete();

        return response()->json(['success' => true], 200);
    }
}
