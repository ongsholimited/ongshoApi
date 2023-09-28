<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Constant;
use App\Models\News\Page;
use App\Models\News\Slug;
use App\Http\Traits\SlugableTrait;
use DB;
use Str;
use Auth;
use Validator;
use DataTables;
class PageController extends Controller
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
        return view('news.pages.pages');
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
         //    return $request->all();
      
        $data=$request->all();
        $validator=Validator::make($data,[
        'title'=>"required|max:250|min:1",
        'meta_description'=>"nullable|max:250|min:1",
        'content'=>"nullable|max:500000|min:1",
        'focus_keyword'=>"nullable|max:500|min:1",
        'slug'=>"required|max:250|min:1|unique:ongsho_news.slugs,slug_name,",
        'status'=>['required','numeric','max:2','min:0'],
    ]);

    // return $data;
    if($validator->passes()){
        DB::transaction(function() use($request,$data){
            $post=Page::create([
                'title'=>$request->title,
                'meta_description'=>$request->meta_description,
                'content'=>$request->content,
                'focus_keyword'=>$request->focus_keyword,
                'status'=>$request->status,
                'author_id'=>auth()->user()->id,
            ]);
            
            Slug::create([
                'slug_name'=> SlugableTrait::makeSlug($request->slug),
                'slug_type'=> 'page',
                'page_id'=> $post->id,
            ]);
        });
        return response()->json(['status'=>true,'message'=>'Post Added Success']);
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
        //
    }
    public function list()
    {
        // return $get=Page::with('slug')->get();
        if(request()->ajax()){
            $get=Page::with('slug')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
              $button.='<a href="'.route('news.badge.edit',$get->id).'"   class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>';
              $button.='<a href="'.route('news.badge.destroy',$get->id).'"  class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('slug',function($get){
            return $get->slug->slug_name;
        })
        ->addColumn('status',function($get){
            return $get->status ? 'Active' : 'Deactive';
        })
          ->rawColumns(['action'])->make(true);
        }
        return view('news.pages.list');
    }
}
