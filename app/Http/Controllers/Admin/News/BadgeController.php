<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\UserHasBadge;
use Illuminate\Http\Request;
use Validator;
use DataTables;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        if(request()->ajax()){
            $get=UserHasBadge::with('user')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
              // $button.='<a data-url="'.route('news.badge.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              $button.='<a data-url="'.route('news.badge.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('user',function($get){
            return $get->user->first_name.' '.$get->user->last_name;
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('news.badge.badge');
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
        $validator=Validator::make($request->all(),[
            'user'=>"required|max:200|min:1",
            'badge_name'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
          
            $insert=UserHasBadge::create([
                'badge_key'=>$request->badge_name,
                'user_id'=>$request->user,
                'author_id'=>auth()->user()->id,
            ]);
               
            if ($insert) {
                return response()->json(['message'=>'Badge Added Success']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
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
        $delete=UserHasBadge::find($id)->delete();
        if($delete){
            return response()->json(['status'=>true,'message'=>'Badge Deleted Success']);   
        }
            return response()->json(['status'=>true,'error'=>'Something went wrong']);   
    }
}