<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Location;
use App\Models\Receive;
use App\Models\Stock;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function stockbalance(Request $request)
    {
     //   dd($request);

     $items = Receive::with('item')->groupBy('item_id')
     ->selectRaw('sum(price_usd*qty) as total_usd')
     ->selectRaw('sum(price_mmk*qty) as total_mmk, sum(qty) as total_qty, item_id')
     ->get();
     //dd($items);

        $location_total = null;
        $stocks = Stock::with('item')->groupBy('item_id','received_date','price_usd','price_mmk','expired_date')       
        ->selectRaw('sum(price_mmk*qty) as total_mmk, sum(qty) as total_qty, received_date, item_id, expired_date, price_usd, price_mmk')  
        ->selectRaw('sum(price_usd*qty) as total_usd')
        ->where('location_id', Location::where('name',$request->location)->first()->id)       
        ->get();
        $stockbyitem = Stock::groupBy('item_id')       
        ->selectRaw('sum(price_mmk*qty) as total_mmk, item_id')  
        ->selectRaw('sum(price_usd*qty) as total_usd')
        ->selectRaw('sum(qty) as total_qty')
        ->where('location_id', Location::where('name',$request->location)->first()->id)       
        ->get();
        
   

       
        $pdf = Pdf::loadView('pdf.stockbalance',['data' => $stocks,'data2'=>$stockbyitem,'location'=>$request->location])->setPaper('a4', 'landscape');
 
        return $pdf->download();
       
    }
    public function stocksummary(Request $request)
    {
        //dd($request->date);
        $location_total = null;
        $stocks = Stock::with('item','location','supplier')->groupBy('invoice_no','supplier_id','location_id','currency')
        ->selectRaw('sum(price_mmk*qty) as total_mmk, supplier_id, invoice_no, location_id,currency')  
        ->selectRaw('sum(price_usd*qty) as total_usd')
        ->where('received_date',$request->date)
        ->whereIn('location_id',['11','1','12','9','15','13'])
        ->get();
        //dd($stocks);
      $usd = Stock::groupBy('currency')
        ->selectRaw('sum(price_usd*qty) as grand_usd,currency')   
        ->where('currency','USD')     
        ->where('received_date',$request->date)
        ->whereIn('location_id',['11','1','12','9','15','13'])
        ->first();   
       $mmk = Stock::groupBy('currency')
        ->selectRaw('sum(price_mmk*qty) as grand_mmk,currency')   
        ->where('currency','MMK')     
        ->where('received_date',$request->date)
        ->whereIn('location_id',['11','1','12','9','15','13'])
        ->first();   
        $loc_total = Stock::groupBy('location_id')
        ->selectRaw('sum(price_mmk*qty) as mmk')   
        ->selectRaw('sum(price_usd*qty) as usd')  
        ->where('location_id','11')           
        ->where('received_date',$request->date)
        ->first();  
        $location_total[1]=$loc_total;

        $loc_total2 = Stock::groupBy('location_id')
        ->selectRaw('sum(price_mmk*qty) as mmk')   
        ->selectRaw('sum(price_usd*qty) as usd')  
        ->where('location_id','1')           
        ->where('received_date',$request->date)
        ->first(); 
        $location_total[2]=$loc_total2;
        $loc_total3 = Stock::groupBy('location_id')
        ->selectRaw('sum(price_mmk*qty) as mmk')   
        ->selectRaw('sum(price_usd*qty) as usd')  
        ->where('location_id','12')           
        ->where('received_date',$request->date)
        ->first();  
        $location_total[3]=$loc_total3;   
        $loc_total4 = Stock::groupBy('location_id')
        ->selectRaw('sum(price_mmk*qty) as mmk')   
        ->selectRaw('sum(price_usd*qty) as usd')  
        ->where('location_id','9')           
        ->where('received_date',$request->date)
        ->first();  
        $location_total[4]=$loc_total4;   
        $loc_total5 = Stock::groupBy('location_id')
        ->selectRaw('sum(price_mmk*qty) as mmk')   
        ->selectRaw('sum(price_usd*qty) as usd')  
        ->where('location_id','15')           
        ->where('received_date',$request->date)
        ->first();  
        $location_total[5]=$loc_total5;   
        $loc_total6 = Stock::groupBy('location_id')
        ->selectRaw('sum(price_mmk*qty) as mmk')   
        ->selectRaw('sum(price_usd*qty) as usd')  
        ->where('location_id','13')           
        ->where('received_date',$request->date)
        ->first();  
        $location_total[6]=$loc_total6;   

       
        $pdf = Pdf::loadView('pdf.stocksummary',['data' => $stocks,'usd'=>$usd,'mmk'=>$mmk,'loc'=>$location_total])->setPaper('a4', 'landscape');
 
        return $pdf->download();
       // return response()->json($stocks);
    }
    public function ledger(Request $request)
    {
       // dd($request);

     $items = Transaction::orderBy(Item::select('item_code')
     ->whereColumn('transactions.item_id', 'items.id'))          
     ->get();
  

     $itemgroupby = Transaction::groupBy('item_id','group_code')  
      ->selectRaw('sum(in_mmk*in_qty) as total_in_mmk') 
      ->selectRaw('sum(in_usd*in_qty) as total_in_usd')   
      ->selectRaw('sum(out_mmk*out_qty) as total_out_mmk') 
      ->selectRaw('sum(out_usd*out_qty) as total_out_usd, item_id, group_code')
      ->selectRaw('sum(in_qty) as total_in_qty') 
      ->selectRaw('sum(out_qty) as total_out_qty')  
     
     ->get();
     $itemcategory = Transaction::groupBy('group_code')  
     ->selectRaw('sum(in_mmk*in_qty) as total_in_mmk') 
      ->selectRaw('sum(in_usd*in_qty) as total_in_usd')   
      ->selectRaw('sum(out_mmk*out_qty) as total_out_mmk') 
      ->selectRaw('sum(out_usd*out_qty) as total_out_usd, group_code')
      ->selectRaw('sum(in_qty) as total_in_qty') 
      ->selectRaw('sum(out_qty) as total_out_qty')       
     ->get();
    //  dd($itemgroupby);
        $location_total = null;
        $stocks = Stock::with('item')->groupBy('item_id','received_date','price_usd','price_mmk')       
        ->selectRaw('sum(price_mmk*qty) as total_mmk, sum(qty) as total_qty, received_date, item_id, price_usd, price_mmk')  
        ->selectRaw('sum(price_usd*qty) as total_usd')
        ->where('location_id', Location::where('name',$request->location)->first()->id)       
        ->get();
        $stockbyitem = Stock::groupBy('item_id')       
        ->selectRaw('sum(price_mmk*qty) as total_mmk, item_id')  
        ->selectRaw('sum(price_usd*qty) as total_usd')
        ->selectRaw('sum(qty) as total_qty')
        ->where('location_id', Location::where('name',$request->location)->first()->id)       
        ->get();
        
   

       
        $pdf = Pdf::loadView('pdf.ledger',['data' => $itemgroupby,'data2'=>$itemcategory,'location'=>$request->location])->setPaper('a4', 'portrait');
 
        return $pdf->download();
       
    }
    public function takingform(Request $request)
    {
       // dd($request);

   
        $location_total = null;
        $stocks = Stock::where('location_id', Location::where('name',$request->location)->first()->id)       
        ->get();

        // $stockbycategory= Stock::with('item.group_by')  
        // ->groupBy('group_code')   
        // ->selectRaw('group_code')  
        // ->where('location_id', Location::where('name',$request->location)->first()->id)  
        
        // ->get();
        $stockbycategory=Stock::query()->where('location_id', Location::where('name',$request->location)->first()->id)        
        ->get()->groupBy('item.group_code');
        $stockby = Stock::with('item.category')
        ->leftjoin('items', 'stocks.item_id','=','items.id')
        ->leftjoin('categories','items.group_code','=','categories.group_code')
        ->groupBy('categories.group_code','categories.name')
        ->selectRaw('categories.group_code, categories.name')
        ->get();
        //dd($stockbycategory);

       
        $pdf = Pdf::loadView('pdf.takingform',['data' => $stocks,'data2'=>$stockby,'location'=>$request->location])->setPaper('a4', 'portrait');
 
        return $pdf->download();
       
    }
   
}
