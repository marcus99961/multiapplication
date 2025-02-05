<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Issue;
use App\Models\Issueitem;
use App\Models\Issueno;
use App\Models\Item;
use App\Models\Location;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $issue = Issueno::with('department','location')
        ->orderBy('name')
        ->where('post_status','no')
        ->get();
        return response()->json($issue);
    }
    public function indexitem(Request $request)    
    {
        $issue_no = Issueno::find($request->keyword);
        $issue = Issueitem::with('department','location','item')
        
        ->where('location_id',$issue_no->location_id)
        ->where('issue_no',$issue_no->name)
        ->get();
        return response()->json($issue);
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
            'name'=> 'required|unique:issuenos,name',
            'department_id'=> 'required',
            'location_id'=>'required',
            'issue_date'=>'required',
           
          

           ],
           [
            'name.required'=>'Please input Name.',
            'name.min'=>"It's too short",
           ]
        );
           
        $invoice = new Issueno();
        $invoice->name = $request->name;
        $invoice->department_id = Department::where('name',$request->department_id)->first()->id;       
        $invoice->location_id = Location::where('name',$request->location_id)->first()->id;       
        $invoice->issue_date = $request->issue_date;
        $invoice->user_id = Auth::user()->id; 
       

        $invoice->save();
    }
    public function storeitems(Request $request, $id)
    {
       //dd($request);
       $issue_nos =Issueno::where('id',$id)->first();
      
       $validateData = $request->validate([
     // 'item_name' => 'required|unique:req_records,item_id,' . $request->item_id . ',id,req_id,' . $req_id,
        'item_id'=>'required|unique:issueitems,item_id,' .$request->item_id . ',id,issue_no,'.$issue_nos->name ,
        'qty' => 'required',
       ],
       [
        'item_id.unique' => 'You already put this item with same issue no.',
    ]); 
      

    
      // $item_id = Item::where('name',$request->item_name)->first();
     // $issue_nos = Issueno::find($id);
           
        $issue = new Issueitem();
        $issue->issue_no = $issue_nos->name;
        $issue->department_id = $issue_nos->department_id;       
        $issue->location_id = $issue_nos->location_id; 
        $issue->item_id = $request->item_id;
        $issue->issue_date = $issue_nos->issue_date;
        $issue->qty = $request->qty;
       

        $issue->save();
    }
    public function post($id)
    {
        $issue = Issueno::find($id);
        $issues = Issueitem::where('issue_no',$issue->name)->get();
        
        foreach($issues as $issued){
            $stocks = Stock::where('location_id',$issue->location_id)->where('item_id',$issued->item_id)
            ->orderBy('received_date')->get();
            $balqty = $issued->qty;
            foreach($stocks as $stock){
           
                if($balqty <= $stock->qty){
                   
                    $issue_item = new Issue();
                    $issue_item->issue_no = $issue->name;
                    $issue_item->issue_date = $issue->issue_date;
                    $issue_item->location_id = $issue->location_id;
                    $issue_item->department_id = $issue->department_id;
                    $issue_item->item_id = $issued->item_id;
                    $issue_item->qty = $balqty;
                    $issue_item->invoice_no =  $stock->invoice_no;
                    $issue_item->received_date =  $stock->received_date;
                    $issue_item->supplier_id =  $stock->supplier_id;
                    $issue_item->currency =  $stock->currency;
                    $issue_item->price_usd =  $stock->price_usd;
                    $issue_item->price_mmk =  $stock->price_mmk;
                    $issue_item->expired_date =  $stock->expired_date;
                    $issue_item->stock_id =  $stock->id;
                    $issue_item->save();
                    
                    $transaction = new Transaction();
                    $transaction->location_id = $issue->location_id;
                    $transaction->item_id =$issued->item_id;
                    $transaction->group_code = Item::where('id',$issued->item_id)->first()->group_code;
                    $transaction->transaction_no = $issue->name;
                    $transaction->date = $issue->issue_date;
                    $transaction->currency = $stock->currency;
                    $transaction->out_qty = $balqty;
                    $transaction->out_mmk = $stock->price_mmk;
                    $transaction->out_usd = $stock->price_usd;
                    $transaction->action = 'out';
                    $transaction->save();
                   

                        $lastqty =  $stock->qty - $balqty;
                        if($lastqty== '0'){
                            $stock->delete();
            
                        }else{
                            Stock::find($stock->id)->update([
                                'qty' => $stock->qty - $balqty,
                            ]); 
                        }
                    
                    $balqty = 0;
                    break;
            }else{
              
               
                $issue_item = new Issue();
                $issue_item->issue_no = $issue->name;
                $issue_item->issue_date = $issue->issue_date;
                $issue_item->location_id = $issue->location_id;
                $issue_item->department_id = $issue->department_id;
                $issue_item->item_id = $issued->item_id;
                $issue_item->qty = $stock->qty;
                $issue_item->invoice_no =  $stock->invoice_no;
                $issue_item->received_date =  $stock->received_date;
                $issue_item->supplier_id =  $stock->supplier_id;
                $issue_item->currency =  $stock->currency;
                $issue_item->price_usd =  $stock->price_usd;
                $issue_item->price_mmk =  $stock->price_mmk;
                $issue_item->expired_date =  $stock->expired_date;
                $issue_item->stock_id =  $stock->id;
                $issue_item->save();
                Transaction::create([
                    'location_id'=> $issue->location_id,
                    'item_id' => $issued->item_id,
                    'transaction_no'=> $issue->name,
                    'date' => $issue->issue_date,
                    'currency' => $stock->currency,
                    'qty' => $stock->qty,
                    'in_mmk' => $stock->price_mmk,
                    'in_usd' => $stock->price_usd,
                    'action' => 'out',
                ]);

                    $balqty = $issued->qty - $stock->qty;
                    $stock->delete();
                }
               

            

            
            }
          


        }
       
       $issue->update(['post_status'=>'yes']);
    }
    public function undoPosting(Request $request)
    {
        $issueno = Issueno::where('name',$request->issue_no)->first();
        $issues = Issueitem::where('issue_no',$request->issue_no)->get();
        $issueitems = Issue::where('issue_no',$request->issue_no)->get();
        $transactions = Transaction::where('transaction_no', $request->issue_no)->where('action','out')->get();
       

        foreach($issueitems as $issueitem){
            $stock = Stock::where('id',$issueitem->stock_id)           
            ->first();
         
                if($stock){
                    $stock->update([
                        'qty'=> $stock->qty + $issueitem->qty,
                    ]);
                }else{
                    Stock::create([
                        'invoice_no' => $issueitem->invoice_no,
                        'received_date' => $issueitem->received_date,
                        'item_id' => $issueitem->item_id,
                        'location_id' => $issueitem->location_id,
                        'supplier_id' => $issueitem->supplier_id,
                        'currency' => $issueitem->currency,
                        'price_usd' => $issueitem->price_usd,
                        'price_mmk' => $issueitem->price_mmk,
                        'qty' => $issueitem->qty,
                        'expired_date' => $issueitem->expired_date,
                    ]);
                    
                }
              
            }
        
       
            $issueno->update(['post_status'=>'no']);
            foreach($issueitems as $stock){
                $stock->delete();
            }  
            foreach($transactions as $transaction){
                $transaction->delete();
            }
       
       
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
        $issue = Issueno::with('location')->where('id',$request->keyword)->first();
        return response()->json($issue);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
         
            'qty'=>'required',
           
         

           ],
           [
            'qty.required'=>'Please input qty.',
            
           ]
        );
        Issueitem::where('id',$request->id)->update([
            'qty'=>$request->qty,
       
          
           ]);
           
    
      
            return response()->json('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issueno $issue)
    {
       $receives = Issueitem::where('issue_no',$issue->name)->delete();
       $issue->delete();

       return response()->noContent();
    }
    public function destroyitem(Issueitem $issue)
    {
     //  $receives = Issueitem::where('issue_no',$issue->name)->delete();
       $issue->delete();

       return response()->noContent();
    }
}
