<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Location;
use App\Models\Receive;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Laravel\Prompts\select;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function locationitems()
    {
        $stocks = Stock::groupBy('location_id')
        ->selectRaw('sum(qty) as quantity, location_id')
        
        ->get();
      
        return response()->json($stocks);
    }
    public function stockstore()
    {
        $stocks = Stock::with('item')->groupBy('item_id')
        ->selectRaw('sum(qty) as quantity, item_id')
        
        ->get();
      
        return response()->json($stocks);
    }
    public function stockbylocations(Request $request)
    {
       // dd($request);
        $stocks = Stock::with('item')->groupBy('location_id','item_id')
        ->selectRaw('sum(qty) as quantity, item_id')        
        ->where(function($query) use ($request){
            $query->where(Item::select('name')
            ->whereColumn('stocks.item_id', 'items.id'), 'like', '%'.$request->keyword.'%')
            ->orWhere(Item::select('item_code')
            ->whereColumn('stocks.item_id', 'items.id'), 'like', '%'.$request->keyword.'%');
                         
            })  
        ->where('location_id',$request->location_id)    
        ->orderBy(Item::select('name')
        ->whereColumn('stocks.item_id', 'items.id'))
        ->get();
      //  dd($stocks);
      
        return response()->json($stocks);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function transferitem(Request $request)
    {
        $transfers = Stock::where('item_id',Item::where('name',$request->item_name)->first()->id)
        ->where('location_id', Location::where('name',$request->source)->first()->id)
        ->orderBy('received_date')->get();
        $balqty = $request->qty;
        foreach($transfers as $transfer){
           
                if($balqty <= $transfer->qty){
                    $existingstock = Stock::where('item_id',Item::where('name',$request->item_name)->first()->id)
                    ->where('location_id', Location::where('name',$request->destination)->first()->id)
                    ->where('invoice_no',$transfer->invoice_no)
                    ->first();
                  //  dd($existingstock);
                    if($existingstock){
                        $existingstock->update([
                            'qty' => $existingstock->qty + $balqty,
                        ]);
                        $lastqty =  $transfer->qty - $balqty;
                        if($lastqty== '0'){
                            $transfer->delete();
            
                        }else{
                            Stock::find($transfer->id)->update([
                                'qty' => $transfer->qty - $balqty,
                            ]); 
                        }
                    }else{
                        Stock::create([
                            'invoice_no' => $transfer->invoice_no,
                            'received_date' => $transfer->received_date,
                            'item_id' => $transfer->item_id,
                            'location_id' => Location::where('name',$request->destination)->first()->id,
                            'supplier_id' => $transfer->supplier_id,
                            'currency' => $transfer->currency,
                            'price_usd' => $transfer->price_usd,
                            'price_mmk' => $transfer->price_mmk,
                            'qty' => $balqty,
                            'expired_date' => $transfer->expired_date,
                        ]);
                        $lastqty =  $transfer->qty - $balqty;
                        if($lastqty== '0'){
                            $transfer->delete();
            
                        }else{
                            Stock::find($transfer->id)->update([
                                'qty' => $transfer->qty - $balqty,
                            ]); 
                        }
                    }
          
          
          
           
            $balqty = 0;
            return response()->json('success');
            
            }else{
                $existingstock = Stock::where('item_id',Item::where('name',$request->item_name)->first()->id)
                ->where('location_id', Location::where('name',$request->destination)->first()->id)
                ->where('invoice_no',$transfer->invoice_no)
                ->first();
              //  dd($existingstock);
                if($existingstock){
                    $existingstock->update([
                        'qty' => $existingstock->qty + $transfer->qty,
                    ]);
                    $balqty = $request->qty - $transfer->qty;
                    $transfer->delete();
                }else{
                    Stock::create([
                        'invoice_no' => $transfer->invoice_no,
                        'received_date' => $transfer->received_date,
                        'item_id' => $transfer->item_id,
                        'location_id' => Location::where('name',$request->destination)->first()->id,
                        'supplier_id' => $transfer->supplier_id,
                        'currency' => $transfer->currency,
                        'price_usd' => $transfer->price_usd,
                        'price_mmk' => $transfer->price_mmk,
                        'qty' => $transfer->qty,
                        'expired_date' => $transfer->expired_date,
                    ]);
                    $balqty = $request->qty - $transfer->qty;
                    $transfer->delete();
                }
               

            }

            
        }
        

        // if($request->qty < $transfer->qty){
        //     Stock::create([
        //         'invoice_no' => $transfer->invoice_no,
        //         'received_date' => $transfer->received_date,
        //         'item_id' => $transfer->item_id,
        //         'location_id' => Location::where('name',$request->destination)->first()->id,
        //         'supplier_id' => $transfer->supplier_id,
        //         'currency' => $transfer->currency,
        //         'price_usd' => $transfer->price_usd,
        //         'price_mmk' => $transfer->price_mmk,
        //         'qty' => $request->qty,
        //         'expired_date' => $transfer->expired_date,
        //     ]);
        //     Stock::find($transfer->id)->update([
        //         'qty' => $transfer->qty - $request->qty,
        //     ]);
        // }elseif($request->qty > $transfer->qty){
        //     $balance_transfer1 = $request->qty - $transfer->qty;
        //     Stock::create([
        //         'invoice_no' => $transfer->invoice_no,
        //         'received_date' => $transfer->received_date,
        //         'item_id' => $transfer->item_id,
        //         'location_id' => Location::where('name',$request->destination)->first()->id,
        //         'supplier_id' => $transfer->supplier_id,
        //         'currency' => $transfer->currency,
        //         'price_usd' => $transfer->price_usd,
        //         'price_mmk' => $transfer->price_mmk,
        //         'qty' => $transfer->qty,
        //         'expired_date' => $transfer->expired_date,
        //     ]);
        //     $transfer->delete();

        // }
        // $transfer2 = Stock::where('item_id',Item::where('name',$request->item_name)->first()->id)
        // ->where('location_id', Location::where('name',$request->source)->first()->id)
        // ->orderBy('received_date')->first();

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
