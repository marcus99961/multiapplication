<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCloseMailQueue;
use App\Jobs\SendDailyReport;
use App\Jobs\SendEmailQueueJob;
use App\Jobs\SendNotiMailQueue;
use App\Jobs\SendUpdateMailQueue;
use App\Mail\CloseMail;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Complaintroom;
use App\Models\Complaintmember;
use App\Models\Department;
use App\Models\Room;
use App\Models\Attachment;
use App\Models\Complaintattachmentuser;
use Attribute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Mail\NotiMail;
use App\Mail\UpdateMail;
use App\Models\Complaintmessageuser;
use App\Models\Member;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Barryvdh\DomPDF\Facade\Pdf;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $complaints = Complaint::with('department')->with('user')->get();
        $keyword = $request->keyword;
            $complaint2 = \DB::table('complaints')
            ->join('departments','complaints.department_id','departments.id')
            ->join('users','complaints.user_id','users.id')
            ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id')
            // ->addSelect(\DB::raw('DATE_FORMAT(complaints.date, "%d-%-%Y") as formatted_date'))
            ->wherenot('complaints.status',$request->close_status)
            ->where(function($query) use ($keyword){
                $query->where('complaints.defect', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('complaints.id', 'LIKE', '%'.$keyword.'%');
                   
            })
            ->orderBy('created_at','desc')
            ->paginate(15);
            // ->limit(100)
            // ->get();
            return response()->json($complaint2);

    }
    public function find(request $request)
    {
       //dd($request);
       if(!$request->keyword['from']){
        $from ='2020-01-01';
       }else{
        $from =$request->keyword['from'];
       }
       if(!$request->keyword['to']){
        $to= Carbon::today();
       }else{
        $to =$request->keyword['to'];
       }
        $complaints = Complaint::with('department')->with('user')->get();
        if($request->keyword['source']=='both' || $request->keyword['source']==null){
            $source = ['admin','guest'];
        }else{
            $source =[$request->keyword['source']];
        }
        if(!$request->keyword['selectedDepartment']){
            $department_id = Department::pluck('id')->toArray();
            
        }else{
            $department_id = Department::where('name',$request->keyword['selectedDepartment'])->pluck('id')->toArray();
        }
        if($request->keyword['selectedCategory']==null && $request->keyword['status']==null){
            $complaint2 = \DB::table('complaints')
            ->join('departments','complaints.department_id','departments.id')
            ->join('users','complaints.user_id','users.id')
            ->join('categories','complaints.category_id','categories.id')
            ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')          
            ->where(function($query) use ($request,$source,$department_id,$from,$to){
                $query->whereIn('complaints.department_id', $department_id)
                ->whereIn('complaints.source',$source )
                ->where('complaints.defect','like','%'.$request->keyword['query'].'%')
                ->whereBetween('date',[$from,$to]);
            })
            ->get();
            return response()->json($complaint2);  
        }elseif($request->keyword['selectedCategory']==null && $request->keyword['status'])
        {
            if($request->keyword['source']=='both' || $request->keyword['source']==null){
                $source = ['admin','guest'];
            }else{
                $source =[$request->keyword['source']];
            }
            if(!$request->keyword['selectedDepartment']){
                $department_id = Department::pluck('id')->toArray();
                
            }else{
                $department_id = Department::where('name',$request->keyword['selectedDepartment'])->pluck('id')->toArray();
            }
           // dd($department_id);
            $complaint2 = \DB::table('complaints')
            ->join('departments','complaints.department_id','departments.id')
            ->join('users','complaints.user_id','users.id')
            ->join('categories','complaints.category_id','categories.id')
            ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
            ->where('complaints.status',$request->keyword['status'])
            ->where(function($query) use ($request,$source,$department_id,$from,$to){
               // $query->where('complaints.department_id', Department::where('name',$request->keyword['selectedDepartment'])->first()->id)
                $query->whereIn('complaints.department_id', $department_id)
                ->whereIn('complaints.source',$source )
                ->where('complaints.defect','like','%'.$request->keyword['query'].'%')
                ->whereBetween('date',[$from,$to]);
                    

            })
            ->get();
            return response()->json($complaint2);

        }elseif($request->keyword['selectedCategory'] && !$request->keyword['status'])
        {
            if($request->keyword['source']=='both' || $request->keyword['source']==null){
                $source = ['admin','guest'];
            }else{
                $source =[$request->keyword['source']];
            }
            if(!$request->keyword['selectedDepartment']){
                $department_id = Department::pluck('id')->toArray();
                
            }else{
                $department_id = Department::where('name',$request->keyword['selectedDepartment'])->pluck('id')->toArray();
            }
            $complaint2 = \DB::table('complaints')
            ->join('departments','complaints.department_id','departments.id')
            ->join('users','complaints.user_id','users.id')
            ->join('categories','complaints.category_id','categories.id')
            ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
           
            ->where(function($query) use ($request,$source,$department_id,$from,$to){
                $query->whereIn('complaints.department_id', $department_id)
                 ->where('complaints.category_id', Category::where('name',$request->keyword['selectedCategory'])->first()->id)
                 ->whereIn('complaints.source',$source )
                 ->where('complaints.defect','like','%'.$request->keyword['query'].'%')
                 ->whereBetween('date',[$from,$to]);
                    

            })
            ->get();
            return response()->json($complaint2);

        }elseif($request->keyword['selectedCategory'] && $request->keyword['status'])
        {
            if($request->keyword['source']=='both' || $request->keyword['source']==null){
                $source = ['admin','guest'];
            }else{
                $source =[$request->keyword['source']];
            }

            if(!$request->keyword['selectedDepartment']){
                $department_id = Department::pluck('id')->toArray();
                
            }else{
                $department_id = Department::where('name',$request->keyword['selectedDepartment'])->pluck('id')->toArray();
            }
            $complaint2 = \DB::table('complaints')
            ->join('departments','complaints.department_id','departments.id')
            ->join('users','complaints.user_id','users.id')
            ->join('categories','complaints.category_id','categories.id')
            ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
            ->where('complaints.status',$request->keyword['status'])
            ->where(function($query) use ($request,$source,$department_id,$from,$to){
                $query->whereIn('complaints.department_id', $department_id)
                 ->where('complaints.category_id', Category::where('name',$request->keyword['selectedCategory'])->first()->id)
                 ->whereIn('complaints.source',$source )
                 ->where('complaints.defect','like','%'.$request->keyword['query'].'%')
                 ->whereBetween('date',[$from,$to]);
                    

            })
            ->get();
            return response()->json($complaint2);

        }
           
    }

    public function dailyReport()
    {


            $complaint2 = \DB::table('complaints')
            ->join('departments','complaints.department_id','departments.id')
            ->join('users','complaints.user_id','users.id')
            ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id')
            ->whereNotIn('complaints.status',['project'])
            ->where('complaints.source','guest')
            ->whereBetween('date', [Carbon::today(), Carbon::today()])
            // ->where(function($query) use ($keyword){
            //     $query->where('complaints.defect', 'LIKE', '%'.$keyword.'%')
            //         ->orWhere('complaints.id', 'LIKE', '%'.$keyword.'%');

            // })
            ->get();
            $selectedrooms = \DB::table('complaintrooms')
            ->join('rooms','complaintrooms.room_id','rooms.id')
            ->select('rooms.name','complaintrooms.*')
            ->get();
               // dd($receipients);
              // $receipients = ['aungkyawmyint@inyalakehotel.com'];
               $emailaddresses = Setting::where('id',4)->first();
               $email = Setting::where('id',5)->first();
               $to = explode(',', $email->value);
               $cc = explode(',', $emailaddresses->value);
                $data = $complaint2->all();
                // $data['selectedrooms']=$selectedrooms;
            //    dd($data);
            // Mail::to($receipients)->send(new NotiMail($request));
            SendDailyReport::dispatch($to,$cc,$data,$selectedrooms);
            return response()->json($complaint2);

    }
    public function reportComplaints(Request $request)
    {
        if(!$request->from){
            $from ='2020-01-01';
           }else{
            $from =$request->from;
           }
           if(!$request->to){
            $to= Carbon::today();
           }else{
            $to =$request->to;
           }
            $complaints = Complaint::with('department')->with('user')->get();
            if($request->source=='both' || $request->source==null){
                $source = ['admin','guest'];
            }else{
                $source =[$request->source];
            }
            if(!$request->selectedDepartment){
                $department_id = Department::pluck('id')->toArray();
                
            }else{
                $department_id = Department::where('name',$request->selectedDepartment)->pluck('id')->toArray();
            }
            if($request->selectedCategory==null && $request->status==null){
                $complaint2 = \DB::table('complaints')
                ->join('departments','complaints.department_id','departments.id')
                ->join('users','complaints.user_id','users.id')
                ->join('categories','complaints.category_id','categories.id')
                ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')          
                ->where(function($query) use ($request,$source,$department_id,$from,$to){
                    $query->whereIn('complaints.department_id', $department_id)
                    ->whereIn('complaints.source',$source )
                    ->where('complaints.defect','like','%'.$request->queryin.'%')
                    ->whereBetween('date',[$from,$to]);
                })
                ->get();
              //  return response()->json($complaint2);  
            }elseif($request->selectedCategory==null && $request->status)
            {
                if($request->source=='both' || $request->source==null){
                    $source = ['admin','guest'];
                }else{
                    $source =[$request->source];
                }
                if(!$request->selectedDepartment){
                    $department_id = Department::pluck('id')->toArray();
                    
                }else{
                    $department_id = Department::where('name',$request->selectedDepartment)->pluck('id')->toArray();
                }
               // dd($department_id);
                $complaint2 = \DB::table('complaints')
                ->join('departments','complaints.department_id','departments.id')
                ->join('users','complaints.user_id','users.id')
                ->join('categories','complaints.category_id','categories.id')
                ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
                ->where('complaints.status',$request->status)
                ->where(function($query) use ($request,$source,$department_id,$from,$to){
                   // $query->where('complaints.department_id', Department::where('name',$request->selectedDepartment)->first()->id)
                    $query->whereIn('complaints.department_id', $department_id)
                    ->whereIn('complaints.source',$source )
                    ->where('complaints.defect','like','%'.$request->queryin.'%')
                    ->whereBetween('date',[$from,$to]);
                        
    
                })
                ->get();
              //  return response()->json($complaint2);
    
            }elseif($request->selectedCategory && !$request->status)
            {
                if($request->source=='both' || $request->source==null){
                    $source = ['admin','guest'];
                }else{
                    $source =[$request->source];
                }
                if(!$request->selectedDepartment){
                    $department_id = Department::pluck('id')->toArray();
                    
                }else{
                    $department_id = Department::where('name',$request->selectedDepartment)->pluck('id')->toArray();
                }
                $complaint2 = \DB::table('complaints')
                ->join('departments','complaints.department_id','departments.id')
                ->join('users','complaints.user_id','users.id')
                ->join('categories','complaints.category_id','categories.id')
                ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
               
                ->where(function($query) use ($request,$source,$department_id,$from,$to){
                    $query->whereIn('complaints.department_id', $department_id)
                     ->where('complaints.category_id', Category::where('name',$request->selectedCategory)->first()->id)
                     ->whereIn('complaints.source',$source )
                     ->where('complaints.defect','like','%'.$request->queryin.'%')
                     ->whereBetween('date',[$from,$to]);
                        
    
                })
                ->get();
                
             //   return response()->json($complaint2);
    
            }elseif($request->selectedCategory && $request->status)
            {
                if($request->source=='both' || $request->source==null){
                    $source = ['admin','guest'];
                }else{
                    $source =[$request->source];
                }
    
                if(!$request->selectedDepartment){
                    $department_id = Department::pluck('id')->toArray();
                    
                }else{
                    $department_id = Department::where('name',$request->selectedDepartment)->pluck('id')->toArray();
                }
                $complaint2 = \DB::table('complaints')
                ->join('departments','complaints.department_id','departments.id')
                ->join('users','complaints.user_id','users.id')
                ->join('categories','complaints.category_id','categories.id')
                ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
                ->where('complaints.status',$request->status)
                ->where(function($query) use ($request,$source,$department_id,$from,$to){
                    $query->whereIn('complaints.department_id', $department_id)
                     ->where('complaints.category_id', Category::where('name',$request->selectedCategory)->first()->id)
                     ->whereIn('complaints.source',$source )
                     ->where('complaints.defect','like','%'.$request->queryin.'%')
                     ->whereBetween('date',[$from,$to]);
                        
    
                })
                ->get();
                //return response()->json($complaint2);
               // dd($complaint2);    
            }

            $selectedrooms = \DB::table('complaintrooms')
            ->join('rooms','complaintrooms.room_id','rooms.id')
            ->select('rooms.name','complaintrooms.*')
            ->get();

            $pdf = Pdf::loadView('pdf.report',['data' => $complaint2,'selectedrooms'=>$selectedrooms])->setPaper('a4', 'landscape');;
 
            return $pdf->download();




        // $this->fpdf = new Fpdf();
        // $width_cell=array(10,20,85,65,35,25,25,25,20,30,219);
        // $this->fpdf->AddPage('L');
        // $this->fpdf->SetFont('Times', 'B', 11);
       
        // $this->fpdf->Cell($width_cell[0], 10, 'Sr',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Date',1,0,'C');
        // $this->fpdf->Cell($width_cell[2], 10, 'Defect',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Room',1,0,'C');
        // $this->fpdf->Cell($width_cell[3], 10, 'Action',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Atten',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Status',1,0,'C');
        // $this->fpdf->Cell($width_cell[4], 10, 'remark',1,1,'C');
        // $i=1;
        // $this->fpdf->SetFont('Times', '', 10);
        // foreach($complaint2 as $dat) {
        //     $this->fpdf->Cell($width_cell[0], 8, $i++,1,0,'R');
        //     $this->fpdf->Cell($width_cell[1], 8, $dat->date,1,0);       
           
          
        //     $this->fpdf->Cell($width_cell[2], 8, $dat->defect,1,0);
        //     foreach ($selectedrooms as $room){
        //         if($room->complaint_id == $dat->id){
        //             $this->fpdf->Write(5, $room->name.',');
        //         }
        //     }
        //     $this->fpdf->Cell($width_cell[3], 8, $dat->action,1,0,'R');
        //     $this->fpdf->Cell($width_cell[1], 8, $dat->department_id,1,0,'R');
        //     $this->fpdf->Cell($width_cell[1], 8, $dat->status,1,0,'R');
        //     $this->fpdf->Cell($width_cell[4], 8, $dat->remark,1,1,'R');
        // }
      
        // $this->fpdf->Output();

        // exit;
    }

    public function reportComplaintbyRoom(Request $request)
    {
        if(!$request->from){
            $from =Carbon::now()->startOfMonth()->toDateString();
           }else{
            $from =$request->from;
           }
           if(!$request->to){
            $to= Carbon::today();
           }else{
            $to =$request->to;
           }
           $complaintrooms =\DB::table('complaintrooms')
           ->join('complaints','complaintrooms.complaint_id','complaints.id')
           ->join('rooms','complaintrooms.room_id','rooms.id')
           ->join('users','complaints.user_id','users.id')
           ->join('departments','complaints.department_id','departments.id')
           ->join('categories','complaints.category_id','categories.id')
           ->select('complaints.*','departments.name as department_name','users.name as user_fullname','rooms.name as room_no','categories.name as category_name')
           ->whereBetween('complaintrooms.created_at',[$from,$to])
           ->orderBy('rooms.name')
           ->orderBy('complaintrooms.created_at')
           ->get();
        //    dd($complaintrooms);
        //     $complaints = Complaint::with('department')->with('user')->get();
        //     if($request->source=='both' || $request->source==null){
        //         $source = ['admin','guest'];
        //     }else{
        //         $source =[$request->source];
        //     }
        //     if(!$request->selectedDepartment){
        //         $department_id = Department::pluck('id')->toArray();
                
        //     }else{
        //         $department_id = Department::where('name',$request->selectedDepartment)->pluck('id')->toArray();
        //     }
        //     if($request->selectedCategory==null && $request->status==null){
        //         $complaint2 = \DB::table('complaints')
        //         ->join('departments','complaints.department_id','departments.id')
        //         ->join('users','complaints.user_id','users.id')
        //         ->join('categories','complaints.category_id','categories.id')
        //         ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')          
        //         ->where(function($query) use ($request,$source,$department_id,$from,$to){
        //             $query->whereIn('complaints.department_id', $department_id)
        //             ->whereIn('complaints.source',$source )
        //             ->where('complaints.defect','like','%'.$request->queryin.'%')
        //             ->whereBetween('date',[$from,$to]);
        //         })
        //         ->get();
        //       //  return response()->json($complaint2);  
        //     }elseif($request->selectedCategory==null && $request->status)
        //     {
        //         if($request->source=='both' || $request->source==null){
        //             $source = ['admin','guest'];
        //         }else{
        //             $source =[$request->source];
        //         }
        //         if(!$request->selectedDepartment){
        //             $department_id = Department::pluck('id')->toArray();
                    
        //         }else{
        //             $department_id = Department::where('name',$request->selectedDepartment)->pluck('id')->toArray();
        //         }
        //        // dd($department_id);
        //         $complaint2 = \DB::table('complaints')
        //         ->join('departments','complaints.department_id','departments.id')
        //         ->join('users','complaints.user_id','users.id')
        //         ->join('categories','complaints.category_id','categories.id')
        //         ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
        //         ->where('complaints.status',$request->status)
        //         ->where(function($query) use ($request,$source,$department_id,$from,$to){
        //            // $query->where('complaints.department_id', Department::where('name',$request->selectedDepartment)->first()->id)
        //             $query->whereIn('complaints.department_id', $department_id)
        //             ->whereIn('complaints.source',$source )
        //             ->where('complaints.defect','like','%'.$request->queryin.'%')
        //             ->whereBetween('date',[$from,$to]);
                        
    
        //         })
        //         ->get();
        //       //  return response()->json($complaint2);
    
        //     }elseif($request->selectedCategory && !$request->status)
        //     {
        //         if($request->source=='both' || $request->source==null){
        //             $source = ['admin','guest'];
        //         }else{
        //             $source =[$request->source];
        //         }
        //         if(!$request->selectedDepartment){
        //             $department_id = Department::pluck('id')->toArray();
                    
        //         }else{
        //             $department_id = Department::where('name',$request->selectedDepartment)->pluck('id')->toArray();
        //         }
        //         $complaint2 = \DB::table('complaints')
        //         ->join('departments','complaints.department_id','departments.id')
        //         ->join('users','complaints.user_id','users.id')
        //         ->join('categories','complaints.category_id','categories.id')
        //         ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
               
        //         ->where(function($query) use ($request,$source,$department_id,$from,$to){
        //             $query->whereIn('complaints.department_id', $department_id)
        //              ->where('complaints.category_id', Category::where('name',$request->selectedCategory)->first()->id)
        //              ->whereIn('complaints.source',$source )
        //              ->where('complaints.defect','like','%'.$request->queryin.'%')
        //              ->whereBetween('date',[$from,$to]);
                        
    
        //         })
        //         ->get();
                
        //      //   return response()->json($complaint2);
    
        //     }elseif($request->selectedCategory && $request->status)
        //     {
        //         if($request->source=='both' || $request->source==null){
        //             $source = ['admin','guest'];
        //         }else{
        //             $source =[$request->source];
        //         }
    
        //         if(!$request->selectedDepartment){
        //             $department_id = Department::pluck('id')->toArray();
                    
        //         }else{
        //             $department_id = Department::where('name',$request->selectedDepartment)->pluck('id')->toArray();
        //         }
        //         $complaint2 = \DB::table('complaints')
        //         ->join('departments','complaints.department_id','departments.id')
        //         ->join('users','complaints.user_id','users.id')
        //         ->join('categories','complaints.category_id','categories.id')
        //         ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id','categories.name as category_name')
        //         ->where('complaints.status',$request->status)
        //         ->where(function($query) use ($request,$source,$department_id,$from,$to){
        //             $query->whereIn('complaints.department_id', $department_id)
        //              ->where('complaints.category_id', Category::where('name',$request->selectedCategory)->first()->id)
        //              ->whereIn('complaints.source',$source )
        //              ->where('complaints.defect','like','%'.$request->queryin.'%')
        //              ->whereBetween('date',[$from,$to]);
                        
    
        //         })
        //         ->get();
        //         //return response()->json($complaint2);
        //        // dd($complaint2);    
        //     }

        //     $selectedrooms = \DB::table('complaintrooms')
        //     ->join('rooms','complaintrooms.room_id','rooms.id')
        //     ->select('rooms.name','complaintrooms.*')
        //     ->get();

            $pdf = Pdf::loadView('pdf.reportbyroom',['data' => $complaintrooms])->setPaper('a4', 'landscape');
 
            return $pdf->download();




        // $this->fpdf = new Fpdf();
        // $width_cell=array(10,20,85,65,35,25,25,25,20,30,219);
        // $this->fpdf->AddPage('L');
        // $this->fpdf->SetFont('Times', 'B', 11);
       
        // $this->fpdf->Cell($width_cell[0], 10, 'Sr',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Date',1,0,'C');
        // $this->fpdf->Cell($width_cell[2], 10, 'Defect',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Room',1,0,'C');
        // $this->fpdf->Cell($width_cell[3], 10, 'Action',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Atten',1,0,'C');
        // $this->fpdf->Cell($width_cell[1], 10, 'Status',1,0,'C');
        // $this->fpdf->Cell($width_cell[4], 10, 'remark',1,1,'C');
        // $i=1;
        // $this->fpdf->SetFont('Times', '', 10);
        // foreach($complaint2 as $dat) {
        //     $this->fpdf->Cell($width_cell[0], 8, $i++,1,0,'R');
        //     $this->fpdf->Cell($width_cell[1], 8, $dat->date,1,0);       
           
          
        //     $this->fpdf->Cell($width_cell[2], 8, $dat->defect,1,0);
        //     foreach ($selectedrooms as $room){
        //         if($room->complaint_id == $dat->id){
        //             $this->fpdf->Write(5, $room->name.',');
        //         }
        //     }
        //     $this->fpdf->Cell($width_cell[3], 8, $dat->action,1,0,'R');
        //     $this->fpdf->Cell($width_cell[1], 8, $dat->department_id,1,0,'R');
        //     $this->fpdf->Cell($width_cell[1], 8, $dat->status,1,0,'R');
        //     $this->fpdf->Cell($width_cell[4], 8, $dat->remark,1,1,'R');
        // }
      
        // $this->fpdf->Output();

        // exit;
    }
    public function index_user(request $request)
    {
        $complaints = Complaint::with('department')->with('user')->get();
        $keyword = $request->keyword;
            $complaint2 = \DB::table('complaints')
            ->join('departments','complaints.department_id','departments.id')
            ->join('users','complaints.user_id','users.id')
            ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id')
            ->wherenot('complaints.status',$request->close_status)
            ->where('users.department_id',Auth::user()->department_id)
            ->where(function($query) use ($keyword){
                $query->where('complaints.defect', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('complaints.id', 'LIKE', '%'.$keyword.'%');

            })
            ->paginate(50);
            return response()->json($complaint2);

    }
    public function index_service(request $request)
    {
        $complaints = Complaint::with('department')->with('user')->get();
      $keyword = $request->keyword;
            $complaint2 = \DB::table('complaints')
            ->join('departments','complaints.department_id','departments.id')
            ->join('users','complaints.user_id','users.id')
            ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id')
            ->wherenot('complaints.status',$request->close_status)
            ->where('complaints.department_id',Auth::user()->department_id)
            ->where(function($query) use ($keyword){
                $query->where('complaints.defect', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('complaints.id', 'LIKE', '%'.$keyword.'%');

            })
            ->paginate(50);
            return response()->json($complaint2);

    }
    public function index2()
    {
        $complaints = Complaint::with('department')->with('user')->get();
        $complaint2 = \DB::table('complaints')
        ->join('departments','complaints.department_id','departments.id')
        ->join('users','complaints.user_id','users.id')
        ->select('complaints.*','users.name as user_fullname','departments.name as department_name','users.department_id as user_department_id')
        ->where('complaints.date',Carbon::today())
        ->get();

        return response()->json($complaint2);
    }

    public function count()
    {
        $complaint2 = \DB::table('complaints')
        ->join('complaintmessageusers','complaints.id','complaintmessageusers.complaint_id')
        ->select(\DB::raw('count(complaintmessageusers.complaint_id) as message_count','complaintmessageusers.complaint_id' ))
        ->groupBy('complaintmessageusers.complaint_id')
        ->get();

        return response()->json($complaint2);
    }
    public function countcomplaint()
    {
        $complaint2 = \DB::table('complaints')
        ->select(\DB::raw('count(complaints.id) as complaint_count' ))
        ->wheredate('date', Carbon::today())
        ->get();

        return response()->json($complaint2);
    }
    public function attachment()
    {
        $attachment = \DB::table('complaintattachmentusers')
        ->join('attachments','complaintattachmentusers.attachment_id','attachments.id')
        ->select('attachments.name','complaintattachmentusers.*')
        ->get();
        return response()->json($attachment);
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
            'selectedRoom' => 'required',
             'selectedDepartment'=>'required',
            'defect'=>'required|max:500',
            'remark'=>'max:500',
           // 'photo'=>'image',
            // 'currency' => 'required',
            // 'payment_type' => 'required',

           ]);
           if ($request->photo) {
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->photo);
            $img = $image->scale(width: 800);
            $upload_path = 'images/complaint/';
            $image_url = $upload_path.$name;
            $img->toWebp(60)->save($image_url);
            Attachment::create([
                'name' => $image_url
            ]);


        Complaint::create([
            'date' => Carbon::today(),
            'arrival' => $request->arrival,
            'departure'=> $request->departure,
            'guest_name'=>$request->guest_name,
            'defect'=> $request->defect,
            'action' => $request->action,
            'result' => $request ->result,
            'source' => $request->source,
            'priority'=> $request->priority,
            'remark' => $request->remark,
            'user_id'=> Auth::user()->id,
            'department_id'=> Department::where('name',$request->selectedDepartment)->first()->id,
            'category_id'=>Category::where('name',$request->selectedCategory)->first()->id,

        ]);
        $complaint_id = Complaint::latest()->first()->id;
        $attachment_id = Attachment::latest()->first()->id;
        $rooms = $request->selectedRoom;
        Complaintattachmentuser::create([
            'complaint_id' =>$complaint_id,
            'attachment_id' =>$attachment_id,
            'user_id' => Auth::user()->id,
        ]);
        foreach($rooms as $room){
            Complaintroom::create([
                'complaint_id'=> $complaint_id,
                'room_id'=> Room::where('name',$room)->first()->id

            ]);
        }
    }else{
        Complaint::create([
            'date' => Carbon::today(),
            'arrival' => $request->arrival,
            'departure'=> $request->departure,
            'guest_name'=>$request->guest_name,
            'defect'=> $request->defect,
            'action' => $request->action,
            'result' => $request ->result,
            'source' => $request->source,
            'priority'=> $request->priority,
            'remark' => $request->remark,
            'user_id'=> Auth::user()->id,
            'department_id'=> Department::where('name',$request->selectedDepartment)->first()->id,
            'category_id'=>Category::where('name',$request->selectedCategory)->first()->id,

        ]);
        $complaint_id = Complaint::latest()->first()->id;
        $rooms = $request->selectedRoom;

        foreach($rooms as $room){
            Complaintroom::create([
                'complaint_id'=> $complaint_id,
                'room_id'=> Room::where('name',$room)->first()->id

            ]);
        }


    }
    $users = User::where('department_id',Department::where('name',$request->selectedDepartment)->first()->id)->get();
    foreach($users as $user){
        $receipients[] = $user->email;

    }
   // dd($receipients);
    $request["user_department"] = Department::where('id',Auth::user()->department_id)->first();
    $request["user_name"] = Auth::user()->name;
    $data = $request->all();
   // Mail::to($receipients)->send(new NotiMail($request));
   SendNotiMailQueue::dispatch($receipients,$data);
    }

    public function storePhoto(Request $request)
    {
        $validateData = $request->validate([
            'photo' => 'required',


           ]);
           if ($request->photo) {
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time().".".$ext;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->photo);
            $img = $image->scale(width: 800);
            $upload_path = 'images/complaint/';
            $image_url = $upload_path.$name;
            $img->toWebp(60)->save($image_url);
            Attachment::create([
                'name' => $image_url
            ]);



        $attachment_id = Attachment::latest()->first()->id;
        $rooms = $request->selectedRoom;
        Complaintattachmentuser::create([
            'complaint_id' =>$request->id,
            'attachment_id' =>$attachment_id,
            'user_id' => Auth::user()->id,
        ]);

    }
        // foreach($departments as $department){
        //     Complaintdepartment::create([
        //         'complaint_id'=> $complaint_id,
        //         'department_id'=> Department::where('name',$department)->first()->id
        //     ]);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $complaints = Complaint::with('department')->with('user')
            ->where('id',$id)
            ->first();


        return response()->json($complaints);
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
        Complaint::where('id',$request->id)->update([

            'arrival' => $request->arrival,
            'departure'=> $request->departure,
            'guest_name'=>$request->guest_name,
            'defect'=> $request->defect,
            'action' => $request->action,
            'remark' => $request->remark,


        ]);
    }


    public function updateStatus(Request $request)
    {
        if($request->status=='done'){
            $validateData = $request->validate([
                'action' => 'required',
    
    
               ]);  
        }
        $receipients = User::find($request->user_id);
       // dd($receipients->update_notification);
        if($request->status=='assign'){
            Complaint::where('id',$request->id)->update([

                'action' => $request->action,
                'status'=> $request->status,

            ]);
            Complaintmember::where('complaint_id',$request->id)->delete();

            Complaintmember::create([
                'complaint_id' => $request->id,
                'member_id'=> $request->member_id
            ]);
            $member = Member::find($request->member_id);
            $request['member_name']=$member->name;
            $request['member_mobile']= $member->mobile;
            $request["user_name"] = Auth::user()->name;
          //  Mail::to($user)->send(new UpdateMail($request));
            $data = $request->all();
            if(setting('update_mail')=='true' && $receipients->update_notification == '1'){
            SendUpdateMailQueue::dispatch($receipients,$data);
        }
        }else{
            Complaint::where('id',$request->id)->update([

                'action' => $request->action,
                'status'=> $request->status,

            ]);
            $asign_data = Complaintmember::where('complaint_id',$request->id)->where('member_id',$request->member_id)->first();

            if(!$asign_data){
            Complaintmember::where('complaint_id',$request->id)->delete();

            Complaintmember::create([
                'complaint_id' => $request->id,
                'member_id'=> $request->member_id
            ]);
            }
            $member = Member::find($request->member_id);
            $request['member_name']=$member->name;
            $request['member_mobile']= $member->mobile;
            $request["user_name"] = Auth::user()->name;
            $data = $request->all();
          //  dd(setting('update_mail'));
            if(setting('update_mail')=='true' && $receipients->update_notification=='1'){
            SendUpdateMailQueue::dispatch($receipients,$data);
            }
           // Mail::to($user)->send(new UpdateMail($request));
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function close($id)
     {
         $closeComplaint = Complaint::find($id);
         $closeComplaint->update([
            'status' => 'closed'
         ]);
         $users = User::where('department_id',$closeComplaint->department_id)->get();
         foreach($users as $user){
             $receipients[] = $user->email;

         }
         $data['id'] = $id;
         $data['byName'] = Auth::user()->name;
          $cc = Auth::user()->email;
        // Mail::to($receipients)->cc($cc)->send(new CloseMail($request));

         SendCloseMailQueue::dispatch($receipients,$data,$cc);
             return response()->json(['success' => true], 200);


     }

    public function destroy($id)
    {
        $deleteComplaint = Complaint::find($id);
        if($deleteComplaint->user_id == Auth::user()->id){
            $deleteComplaint->delete();
            Complaintroom::where('complaint_id',$id)->delete();
            $messages=Complaintmessageuser::where('complaint_id',$id)->get();
            if($messages){
            foreach($messages as $message){
                Message::where('id',$message->message_id)->delete();
            }
         
            Complaintmessageuser::where('complaint_id',$id)->delete();

            }
           
            $attachments=Complaintattachmentuser::where('complaint_id',$id)->get();
     
            if($attachments){
            foreach($attachments as $attachment){
               $photo_link = Attachment::where('id',$attachment->attachment_id)->first();
               unlink($photo_link->name);
               $photo_link->delete();
              

            }
           
            Complaintattachmentuser::where('complaint_id',$id)->delete();

            }
            return response()->json(['success' => true], 200);
        }else{
            return response()->json(['success' => false], 503);
        }



    }
    public function deletephoto($id)
    {
        $deleteAttachment = Complaintattachmentuser::find($id);
        if($deleteAttachment->user_id == Auth::user()->id){

           $photoLink=Attachment::where('id',$deleteAttachment->attachment_id)->first();
           unlink($photoLink->name);
           $photoLink->delete();
           $deleteAttachment->delete();
            return response()->json(['success' => true], 200);
        }else{
            return response()->json(['success' => false], 503);
        }



    }
}
