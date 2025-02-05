<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $item = Item:: where(function($query) use ($request){
            $query->where('name', 'like', '%'.$request->keyword.'%')
            ->orWhere('group_code', 'like', '%'.$request->keyword.'%');
                         
            })     
        ->orderByDesc('id')->paginate(setting('pagination_limit'));
        return response()->json($item);
    }
    public function index2()
    {
        $item = Item::get();
        return response()->json($item);
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
            'item_code'=> 'required|unique:items,item_code',
            'spec'=>'',
            'unit_measure'=>'',
            'group_code'=>'',

            'min_qty'=>'',
            'max_qty'=>'',
            'reorder_level'=>''

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
        $item = new Item();
        $item->name = $request->name;
        $item->item_code = $request->item_code;
        $item->spec = $request->spec;
        $item->unit_measure = $request->unit_measure;
        $item->group_code = Category::where('name',$request->group_code)->first()->group_code;
        $item->min_qty = $request->min_qty;
        $item->max_qty = $request->max_qty;
        $item->reorder_level = $request->reorder_level;


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
            'name'=> 'required|unique:items,name,'.$id,
            'item_code'=> 'required|unique:items,item_code,'.$id,
            'spec'=>'',
            'unit_measure'=>'',
            'group_code'=>'',

            'min_qty'=>'',
            'max_qty'=>'',
            'reorder_level'=>''

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
        Item::where('id',$request->id)->update([
            'name'=>$request->name,
            'item_code'=> $request->item_code,
            'spec' => $request->spec,
            'unit_measure' => $request->unit_measure,
            'group_code' => Category::where('name',$request->group_code)->first()->group_code,
            'min_qty' => $request->min_qty,
            'max_qty' => $request->max_qty,
            'reorder_level' => $request->reorder_level,
           ]);
           
    
      
            return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->noContent();
    }
}
