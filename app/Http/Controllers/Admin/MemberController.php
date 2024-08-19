<?php

namespace App\Http\Controllers\Admin;
use App\Models\Member;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Member::where('department_id',Auth::user()->department_id)
        ->where('status','active')
        ->get();
        //   $member2= DepartmentResource::collection($member)->toArray(request());
   
           return response()->json($member);
    }
    public function inactive()
    {
        $member = Member::where('department_id',Auth::user()->department_id)
        ->where('status','inactive')
        ->get();
        //   $member2= DepartmentResource::collection($member)->toArray(request());
   
           return response()->json($member);
    }
    public function index2($id)
    {
        $member = Member::where('department_id',$id)
        ->where('status','active')
        ->get();
        //   $member2= DepartmentResource::collection($member)->toArray(request());
   
           return response()->json($member);
    }
    public function complaintmember()
    {
        $member = \DB::table('complaintmembers')
        ->join('members','complaintmembers.member_id','members.id')
        ->select('members.*','complaintmembers.*')
        ->get();
        //   $member2= DepartmentResource::collection($member)->toArray(request());
   
           return response()->json($member);
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
            'name' => 'required|unique:members|min:2',
            'mobile'=> 'required',
            // 'currency' => 'required',
            // 'payment_type' => 'required',

           ]
        );
           
        $member = new Member;
        $member->name = $request->name;
        $member->department_id = Department::where('name',$request->selectedDepartment)->first()->id;
        $member->mobile = $request->mobile;


        $member->save();
        return response()->json('Success');
    }
    public function storebydepartment(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:members|min:2',
            'mobile'=> 'required',
            // 'currency' => 'required',
            // 'payment_type' => 'required',

           ]
        );
           
        $member = new Member;
        $member->name = $request->name;
        $member->department_id = Auth::user()->department_id;
        $member->mobile = $request->mobile;


        $member->save();
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
    public function update(Request $request, $id)
    {
        Member::where('id',$id)->update([
            'name'=> $request->name,
            'mobile'=> $request->mobile,
            'status'=> $request->status
        ]);
        return response()->json('Success');
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
