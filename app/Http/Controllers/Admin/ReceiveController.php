<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Receive;
use App\Models\Stock;
use App\Models\Transaction;

class ReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $receives = Receive::with('item')
       ->where('invoice_no',Invoice::where('id',$request->keyword)->first()->name)
       ->get();
      
      // dd($total);
       return response()->json($receives);
    }
    public function total(Request $request)
    {
        $total = Receive::where('invoice_no',Invoice::where('id',$request->keyword)->first()->name)     
        ->groupBy('invoice_no')
        ->selectRaw('sum(qty*price_mmk) as total_mmk, sum(qty*price_usd) as total_usd')
        ->first();  
        // $receives->total_mmk = $total->total_mmk;
        // $receives->total_usd = $total->total_usd;
        return response()->json($total);
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
    public function store(Request $request,$id)
    {
        $validateData = $request->validate([
            'item_id' => 'required',
        

           ],
           [
            'item_id.required'=>'Please choose.',
  
           ]
        );
        $invoicedata = Invoice::find($id);
           
        $receive = new Receive();
        $receive->item_id = Item::where('name',$request->item_id)->first()->id;
        if($invoicedata->currency==='MMK'){
            $receive->price_mmk = $request->price;
            $receive->price_usd = '0';
        }else{
            $receive->price_usd = $request->price;
            $receive->price_mmk = '0';
        }       
        $receive->currency = $invoicedata->currency;
        $receive->expired_date = $request->expired_date;
        $receive->qty = $request->qty;     
        $receive->location_id = $invoicedata->location_id;        
        $receive->supplier_id = $invoicedata->supplier_id;
        $receive->invoice_no = $invoicedata->name;
        $receive->received_date = $invoicedata->received_date;

    


        $receive->save();
       

  
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
         
            'qty'=>'required',
            'price'=>'required',          

            
         

           ],
           [
            'qty.required'=>'Please input qty.',
            
           ]
        );
        if($request->currency=='MMK'){
            Receive::where('id',$request->id)->update([
                'qty'=>$request->qty,
                'price_mmk'=> $request->price,           
                'expired_date' => $request->expired_date,           
              
               ]);
        }else{
            Receive::where('id',$request->id)->update([
                'qty'=>$request->qty,
                'price_usd'=> $request->price,           
                'expired_date' => $request->expired_date,           
              
               ]);
        }
       
           
    
      
            return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receive $receive)
    {
        $receive->delete();
    }
    
    public function post($id)
    {
        $invoice = Invoice::find($id);
        $receives = Receive::where('invoice_no',$invoice->name)->get();
        foreach($receives as $receive){
            Stock::create([
                'invoice_no' => $receive->invoice_no,
                'received_date' => $receive->received_date,
                'item_id' => $receive->item_id,
                'location_id' => $receive->location_id,
                'supplier_id' => $receive->supplier_id,
                'price_mmk' => $receive->price_mmk,
                'price_usd' => $receive->price_usd,
                'qty' => $receive->qty,
                'currency' => $receive->currency,
                'expired_date' => $receive->expired_date,


            ]);
            $transaction = new Transaction();
            $transaction->location_id = $receive->location_id;
            $transaction->item_id = $receive->item_id;
            $transaction->group_code = Item::where('id',$receive->item_id)->first()->group_code;
            $transaction->transaction_no = $receive->invoice_no;
            $transaction->date = $receive->received_date;
            $transaction->currency = $receive->currency;
            $transaction->in_qty = $receive->qty;
            $transaction->in_mmk = $receive->price_mmk;
            $transaction->in_usd = $receive->price_usd;
            $transaction->action = 'in';
            $transaction->save();
          

        }
       $invoice->update(['post_status'=>'yes']);
    }
    public function undoPosting(Request $request)
    {
        $invoice = Invoice::where('name',$request->invoice_no)->first();
        $receives = Receive::where('invoice_no',$request->invoice_no)->get();
        $stocks = Stock::where('invoice_no',$request->invoice_no)->get();
        $transactions = Transaction::where('transaction_no',$request->invoice_no)->where('action','in')->get();
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
            foreach($transactions as $transaction){
                $transaction->delete();
            }                   
        } else{
            return response()->json($check);
        }
     //   $invoice->update(['post_status'=>'no']);
       
    }
}
