<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\Receive;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoice = Invoice::with('supplier','location')
        ->orderBy('name')
        ->where('post_status','no')
        ->get();
        return response()->json($invoice);
        
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
            'name'=> 'required|unique:invoices,name',
            'supplier_id'=> 'required',
            'location_id'=>'required',
            'received_date'=>'required',
            'currency'=>'required',
          

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
        $invoice = new Invoice();
        $invoice->name = $request->name;
        $invoice->supplier_id = Supplier::where('name',$request->supplier_id)->first()->id;       
        $invoice->location_id = Location::where('name',$request->location_id)->first()->id;
        $invoice->currency = $request->currency;       
        $invoice->received_date = $request->received_date;
        $invoice->user_id = Auth::user()->id; 
       

        $invoice->save();
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
    public function edit(Request $request)
    {
        $invoice_no = Invoice::find($request->keyword);
       // dd($invoice_no);
        return response()->json($invoice_no);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    public function undoPosting(Request $request)
    {
        $invoice = Invoice::where('name',$request->invoice_no)->first();
        $receives = Receive::where('invoice_no',$request->invoice_no)->get();
        $stocks = Stock::where('invoice_no',$request->invoice_no)->get();
        $no = null;
        $check = null;
        foreach($stocks as $stock){
            foreach($receives as $receive){
                if($receive->qty!== $stock->qty or $receive->location_id !== $stock->location_id){
                    if($receive->item_id===$stock->item_id){
                        $check[]= $receive->item->name; 
                    }
                 
                }
            }
        }
        if(!$check){
            $invoice->update(['post_status'=>'no']);
            foreach($stocks as $stock){
                $stock->delete();
            }           
        } else{
            return response()->json($check);
        }
     //   $invoice->update(['post_status'=>'no']);
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
       $receives = Receive::where('invoice_no',$invoice->name)->delete();
       $invoice->delete();

       return response()->noContent();
    }
}
