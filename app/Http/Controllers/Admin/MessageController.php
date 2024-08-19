<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailQueueJob;
use App\Mail\MessageMail;
use App\Models\Complaint;
use App\Models\Complaintmessageuser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $messages = \DB::table('complaintmessageusers')
       ->join('messages','complaintmessageusers.message_id','messages.id')
       ->join('users','complaintmessageusers.user_id','users.id')
       ->join('complaints','complaintmessageusers.complaint_id','complaints.id')
       ->select('messages.*','users.name as userfullname','users.avatar','complaints.id')
       ->wheredate('messages.created_at', Carbon::today())
       ->get();

        $message2 = Complaintmessageuser::wheredate('created_at',Carbon::today())
        ->addSelect(['name' => Message::select('name')
        ->whereColumn('messages.id', 'message_id')
        ])->addSelect(['userfullname'=>User::select('name')->whereColumn('users.id','user_id') ])
        ->addSelect(['id'=>Complaint::select('id')->whereColumn('complaints.id','complaint_id') ])
        ->addSelect(['avatar'=>User::select('avatar')->whereColumn('users.id','user_id') ])       
        ->get();



       return response()->json($message2);
    }
    public function count()
    {
       $messages = \DB::table('complaintmessageusers')
       ->join('messages','complaintmessageusers.message_id','messages.id')
       ->join('users','complaintmessageusers.user_id','users.id')
       ->join('complaints','complaintmessageusers.complaint_id','complaints.id')
       ->select(\DB::raw('count(messages.id) as message_count'))
       ->wheredate('messages.created_at', Carbon::today())
       ->get();
       return response()->json($messages);
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
            'name' => 'required',
            // 'currency' => 'required',
            // 'payment_type' => 'required',

           ],
           [
            'name.required'=>'Please input Message',
           ]
        );
        if($request->name=='undo'){
            $undocomplaint = Complaint::find($request->complaint_id)->update(['status'=>'pending']);
        }
        $message = new Message();
        $message->name = $request->name;


        $message->save();

        $message_id = Message::latest()->first()->id;
        Complaintmessageuser::create([
            'complaint_id'=>$request->complaint_id,
            'message_id'=> $message_id,
            'user_id'=> Auth::user()->id,

        ]);
        $complaint = Complaint::find($request->complaint_id);
        if($complaint->department_id!=Auth::user()->department_id){
            $users = User::where('department_id',$complaint->department_id)->get();
            foreach($users as $user){
                $receipients[] = $user->email;

            }

        }else{
            $user = User::find($complaint->user_id);
            $users = User::where('department_id',$user->department_id)->get();
            foreach($users as $user){
                $receipients[] = $user->email;

            }
        }
        $request['sender']=Auth::user();
        \DB::table('complaints')->where('id',$request->complaint_id)->increment('msg_status');
       
       // Mail::to($receipients)->send(new MessageMail($request));
       $data = $request->all();
        SendEmailQueueJob::dispatch($receipients,$data);
        return response()->json('Success');

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(request $request,$id)
    {

        $messages = \DB::table('complaintmessageusers')
        ->join('messages','complaintmessageusers.message_id','messages.id')
        ->join('complaints','complaintmessageusers.complaint_id','complaints.id')
        ->join('users','complaintmessageusers.user_id','users.id')
        ->select('messages.*','users.name as userfullname','users.avatar','complaintmessageusers.user_id')
        ->where('complaints.id',$id)
        ->orderBy('messages.id')
        ->get();

        return response()->json($messages);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
