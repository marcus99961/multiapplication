<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Lnf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;

class LostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct()
    // {
    //     return $this->middleware('auth:api');
    // }
    public function index(request $request)
    {
        $items = Lnf::where('item_status','active')
            ->where(function($query) use ($request){
            $query->where('item_name', 'like', '%'.$request->keyword.'%')
            ->orWhere('location', 'like', '%'.$request->keyword.'%')
            ->orWhere('finder', 'like', '%'.$request->keyword.'%');                
            })     
        
        ->orderBy('id','desc')->paginate(setting('pagination_limit'));
        return response()->json($items);
    }
    public function ClaimedIndex(request $request)
    {
        $items = Lnf::where('item_status','done')
        ->where(function($query) use ($request){
        $query->where('item_name', 'like', '%'.$request->keyword.'%')
        ->orWhere('location', 'like', '%'.$request->keyword.'%')
        ->orWhere('finder', 'like', '%'.$request->keyword.'%');                
        })      
    
        ->orderBy('id','desc')->limit(50)->get();
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lostcount()
    {
        $lostcount = Lnf::     
        select(\DB::raw('count(lnfs.id) as lost_count' ))
        ->wheredate('created_at', Carbon::today())
        ->get();

        return response()->json($lostcount);
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
            'date' => 'required',
            'location' => 'required',
            'item_name' => 'required',
            'finder' => 'required',
            'safe_location' => 'required'


           ]);
        $last= Lnf::orderBy('id', 'DESC')->first();
        if($last){
            $last_id = $last->id + 200000 ;
        }else{
            $last_id = 200000;
        }
        if ($request->photo) {
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->photo);
            $img = $image->scale(width: 800);
            $upload_path = 'images/lost/';
            $image_url = $upload_path.$name;
            $img->toWebp(60)->save($image_url);
        $item = new Lnf;
        $item->date = $request->date;
        $item->location = $request->location;
        $item->guest_name = $request->guest_name;
        $item->sr_no = $last_id;
        $item->item_name = $request->item_name;
        $item->finder = $request->finder;
        $item->safe_location = $request->safe_location;
        $item->remark = $request->remark;
        $item->photo = $image_url;
        $item->user_id = Auth::user()->id;
        $item->save();
        }else{
        $item = new Lnf;
        $item->date = $request->date;
        $item->location = $request->location;
        $item->guest_name = $request->guest_name;
        $item->sr_no = $last_id;
        $item->item_name = $request->item_name;
        $item->finder = $request->finder;
        $item->safe_location = $request->safe_location;
        $item->remark = $request->remark;
        $item->user_id = Auth::user()->id;
        $item->save();
        }
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
            'date' => 'required',
            'location' => 'required',
            'item_name' => 'required',
            'finder' => 'required',
            'safe_location' => 'required'


           ]);

        if ($request->photo) {
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->photo);
            $img = $image->scale(width: 800);
            $upload_path = 'images/lost/';
            $image_url = $upload_path.$name;
            $success=$img->toWebp(80)->save($image_url);
            
                
                $img = \DB::table('lnfs')->where('id',$request->id)->first();
                 $image_path = $img->photo;

                if($image_path){
                 unlink($image_path);
             }

                Lnf::where('id',$request->id)->update([
                    'item_name'=> $request->item_name,
                    'location'=>$request->location,
                    'guest_name'=>$request->guest_name,
                    'finder'=>$request->finder,
                    'photo'=> $image_url,
                    'remark'=>$request->remark,
                   
        
        
                ]);
             
            }else{
            Lnf::where('id',$request->id)->update([
            'item_name'=> $request->item_name,
            'location'=>$request->location,
            'guest_name'=>$request->guest_name,
            'finder'=>$request->finder,
            'remark'=>$request->remark,


        ]);
    }
    }
    public function claim(Request $request)
    {
        $validateData = $request->validate([
            'claimed_date' => 'required',
            'claimed_by' => 'required',
        


           ]);

        if ($request->attachment) {
            $position = strpos($request->attachment, ';');
            $sub = substr($request->attachment, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->attachment);
            $img = $image->scale(width: 800);
            $upload_path = 'images/lost/';
            $image_url = $upload_path.$name;
            $img->toWebp(60)->save($image_url);
            $img = \DB::table('lnfs')->where('id',$request->id)->first();
            $image_path = $img->attachment;

           if($image_path){
            unlink($image_path);
            }
                Lnf::where('id',$request->id)->update([
                    'claimed_date'=> $request->claimed_date,
                    'claimed_by'=> $request->claimed_by,
                    'item_status'=> 'done',
                    'remark'=>$request->remark,
                    'attachment'=> $image_url,
                   
        
        
                ]);
             
            }else{
                Lnf::where('id',$request->id)->update([
                    'claimed_date'=> $request->claimed_date,
                    'claimed_by'=> $request->claimed_by,
                    'item_status'=> 'done',
                    'remark'=>$request->remark,
                ]);
            }
    }
    public function claimItems(Request $request)
    {
        $validateData = $request->validate([
            'claimed_date' => 'required',
            'claimed_by' => 'required',
        


           ]);

        if ($request->attachment) {
            $position = strpos($request->attachment, ';');
            $sub = substr($request->attachment, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->attachment);
            $img = $image->scale(width: 800);
            $upload_path = 'images/lost/';
            $image_url = $upload_path.$name;
            $img->toWebp(60)->save($image_url);
        //     $img = \DB::table('lnfs')->where('id',$request->id)->first();
        //     $image_path = $img->attachment;

        //    if($image_path){
        //     unlink($image_path);
        //     }
                Lnf::whereIn('id',$request->ids)->update([
                    'claimed_date'=> $request->claimed_date,
                    'claimed_by'=> $request->claimed_by,
                    'item_status'=> 'done',
                    'remark'=>$request->remark,
                    'attachment'=> $image_url,
                   
        
        
                ]);
             
            }else{
                Lnf::whereIn('id',$request->ids)->update([
                    'claimed_date'=> $request->claimed_date,
                    'claimed_by'=> $request->claimed_by,
                    'item_status'=> 'done',
                    'remark'=>$request->remark,
                ]);
            }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lnf $item)
    {
        
        $item->delete();

        return response()->json(['success' => true], 200);
    }
    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        Lnf::whereIn('id', $ids)->delete();
        return response()->json(['success' => true], 200);
    }
    public function reportItems(Request $request) {
       
        $items = Lnf::whereIn('id', $request)->get();
       // dd($items);
       $this->fpdf = new Fpdf();
       $this->fpdf->SetMargins(15,15,15);
        $width_cell=array(10,20,40,55,35,25,25,25,20,30,219);
        $this->fpdf->AddPage('L');
        
        $this->fpdf->SetFont('Times', 'B', 11);
        $this->fpdf->SetFillColor(10,250,10);
        
        $this->fpdf->Cell($width_cell[0], 10, 'Sr',1,0,'C',true);
        $this->fpdf->Cell($width_cell[1], 10, 'Date',1,0,'C',true);
        $this->fpdf->Cell($width_cell[1], 10, 'Sr No.',1,0,'C',true);
        $this->fpdf->Cell($width_cell[2], 10, 'Found@',1,0,'C',true);
        $this->fpdf->Cell($width_cell[2], 10, 'ItemName',1,0,'C',true);
        $this->fpdf->Cell($width_cell[2], 10, 'Finder',1,0,'C',true);
        $this->fpdf->Cell($width_cell[3], 10, 'Remark',1,0,'C',true);
        $this->fpdf->Cell($width_cell[2], 10, 'ClaimedBy',1,1,'C',true);
        $i=1;
        $this->fpdf->SetFont('Times', '', 10);
        foreach($items as $dat) {
            $this->fpdf->Cell($width_cell[0], 11, $i++,1,0,'R');
            $this->fpdf->Cell($width_cell[1], 11,date('d-m-Y', strtotime($dat['date'])) ,1,0);
          
            $this->fpdf->Cell($width_cell[1], 11, $dat['sr_no'],1,0);
            $this->fpdf->Cell($width_cell[2], 11, $dat['location'],1,0,'L');
        
            $this->fpdf->Cell($width_cell[2], 11, $dat['item_name'],1,0,'C');
            $this->fpdf->Cell($width_cell[2], 11, $dat['finder'],1,0,'C');
            $this->fpdf->Cell($width_cell[3], 11, $dat['remark'],1,0,'L');
            $this->fpdf->Cell($width_cell[2], 11, 'sign->',1,1,'L');
        }
      
        $this->fpdf->Output();

        exit;
    }
}
