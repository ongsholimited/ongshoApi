<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Test;
use DataTables;
use Validator;
class TestController extends Controller
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
        $categoryall=Test::all();
       
        $data=
        [
           'form'=>[
            'name'=>'Test',
           ],
           'datatable'=>[
            'name',
            'action',
           ],
           'route'=>route('news.test.index'),
           'fields'=> [
                
                [
                    'name'=>'name',
                    'label'=>'Name',
                    'placeholder'=>'Enter Name',
                    'type'=>'text',
                    'classes'=>'form-control',
                    
                ],
                [
                    'name'=>'roll',
                    'label'=>'Roll',
                    'placeholder'=>'Enter Roll',
                    'type'=>'text',
                    'classes'=>'form-control',

                ],
                [
                    'name'=>'roll',
                    'label'=>'Roll',
                    'placeholder'=>'Enter Roll',
                    'type'=>'text',
                    'classes'=>'form-control',

                ],
                
                
            ]
        ];
        // return $get=Category::with('parent')->get();
        if (request()->ajax()) {
            $get = Test::query();
            return DataTables::of($get)
                ->addIndexColumn()
                ->addColumn('action', function ($get) use ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    $button .= '<a data-url="' . url('crud_maker/edit') . '" data-id="' . strval($get->id) . '" data-form="' . $data['form']['name'] . '"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>';
                    $button .= '<a data-url="' . url('crud_maker/destroy') . '" data-id="' . strval($get->id) . '" data-form="' . $data['form']['name'] . '" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
                    $button .= '</div>';
                    return $button;
                })
                ->addColumn('parent', function ($get) use ($data) {
                    return isset($get->parent->name) ?$get->parent->name: '' ;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('crud_maker.crud_maker',compact('data'));
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
        //
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
        //
    }
}
