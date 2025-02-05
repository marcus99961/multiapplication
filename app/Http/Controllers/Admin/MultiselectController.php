<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Issueno;
use App\Models\Item;
use App\Models\Location;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;
use Svg\Gradient\Stop;

class MultiselectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function supplier()
    {
        $supplier = Supplier::orderBy('name')->pluck('name')->toArray();
        return response()->json($supplier);
    }
    public function departments()
    {
        $department = Department::orderBy('name')->pluck('name')->toArray();
        return response()->json($department);
    }

    public function location()
    {
        $location = Location::orderBy('name')->pluck('name')->toArray();
        return response()->json($location);
    }

    public function unit()
    {
        $unit = Unit::orderBy('name')->pluck('name')->toArray();
        return response()->json($unit);
    }
    public function category()
    {
        $category = Category::orderBy('name')->pluck('name')->toArray();
        return response()->json($category);
    }
    public function department()
    {
        $department = Department::orderBy('name')->pluck('name')->toArray();
        return response()->json($department);
    }
    public function item()
    {
        $item = Item::orderBy('name')->pluck('name')->toArray();
        return response()->json($item);
    }
    public function items()
    {
        $item = Item::orderBy('name')->get();
        return response()->json($item);
    }
    public function invoice()
    {
        $invoice = Invoice::with('supplier','location')
        ->orderBy('id','desc')
        ->where('post_status','yes')
        ->pluck('name')->toArray();
        return response()->json($invoice);
        
    }
    public function issue()
    {
        $issue = Issueno::with('department','location')
        ->orderBy('id','desc')
        ->where('post_status','yes')
        ->pluck('name')->toArray();
        return response()->json($issue);
        
    }
    public function sourcelocations()
    {
        $location = Location::whereIn('id', Stock::pluck('location_id')->toArray())->pluck('name')->toArray();
        return response()->json($location);
    }
    public function sourceitems(Request $request)
    {
        if($request->keyword){
            $items = Stock::where('location_id',Location::where('name',$request->keyword)->first()->id)->get();
            // dd($items);
             $item = Item::orderBy('name')->whereIn('id', $items->pluck('item_id'))->pluck('name')->toArray();
             return response()->json($item);
        }
        
        
    }
    public function sourceitem(Request $request)
    {
      //  dd($request);
      $issue_no = Issueno::find($request->keyword);
        $items = Stock::where('location_id',$issue_no->location_id)->get();
       // dd($request);
        $item = Item::orderBy('name')->whereIn('id', $items->pluck('item_id'))->pluck('name')->toArray();
        return response()->json($item);
    }
    public function sourceinvoices(Request $request)
    {
      //  dd($request);
    //  $issue_no = Issueno::find($request->keyword);
        if($request->location){
            $invoices = Stock::where('location_id',Location::where('name',$request->location)->first()->id)
            ->where('item_id', Item::where('name',$request->item)->first()->id)
            ->groupBy('location_id','item_id','invoice_no')
            ->pluck('invoice_no')->toArray();
           // dd($invoices);
            return response()->json($invoices);
        }
        
    }
    public function sourceitemqty(Request $request)
    {
        
    //     $items = Stock::where('location_id',Location::where('name',$request->keyword)->first()->id)->get();
    //    // dd($items);
    //     $item = Item::orderBy('name')->whereIn('id', $items->pluck('item_id'))->pluck('name')->toArray();
        if($request->location){
            $qty = Stock::where('location_id',Location::where('name',$request->location)->first()->id)
            ->where('item_id', Item::where('name',$request->item)->first()->id)
            // ->where('invoice_no', $request->invoice)
            ->groupBy('location_id','item_id')
            ->selectRaw('sum(qty) as total_qty')
            ->first();  
           // dd($qty);
            return response()->json($qty);
        }
       
    }
    public function sourceitemqtys(Request $request)
    {
        
    //     $items = Stock::where('location_id',Location::where('name',$request->keyword)->first()->id)->get();
    if($request->item){
        $issue_no = Issueno::find($request->location);
        //     $item = Item::orderBy('name')->whereIn('id', $items->pluck('item_id'))->pluck('name')->toArray();
            $qty = Stock::where('location_id',$issue_no->location_id)
            ->where('item_id', Item::where('name',$request->item)->first()->id)
            ->groupBy('location_id','item_id')
            ->selectRaw('sum(qty) as total_qty')
            ->first();  
          
            return response()->json($qty);
    }
       
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
