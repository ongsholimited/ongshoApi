<?php
namespace App\Helpers;
use Validator;
class Crud{
    // formname  register with a Model;
    protected $register=[
        'Category'=>'App\Models\News\Category',
        'Meta_Keyword'=>'App\Models\News\MetaKeyword',
        'News_Setting'=>'App\Models\News\NewsSetting',
    ];
    protected $validation=[
      'Meta_Keyword'=>[
        'title'=>'required|max:120',
        'slug'=>'required|max:120',
        'description'=>'nullable|max:120',
        'keyword'=>'nullable|max:120',
        'robots'=>'nullable|max:120',
      ],
      'Category'=>[],
      'News_Setting'=>[],
    ];
    public function store($data){
        // return $data;
        // $this->register[$data['_name']];
      $crud=$data;
      unset($crud['_name']);
      $validator=Validator::make($crud,$this->validation[$data['_name']]);
      if($validator->passes()){
        $store=$this->register[$data['_name']]::create($crud);
        if($store){
          return response()->json(['status'=>true,'message'=>str_replace('_',' ',$data['_name']).' Added Succes']);
        }
      }
      return response()->json(['status'=>false,'errors'=>$validator->getMessageBag()]);
       
    }
    public function edit($data){
        return $this->register[$data['_name']]::find($data['id']);
    }
    public function update($data){
      $crud=$data;
      unset($crud['_name']);
      unset($crud['form_data_id']);
      $validator=Validator::make($crud,$this->validation[$data['_name']]);

      if($validator->passes()){
        $update= $this->register[$data['_name']]::where('id',$data['form_data_id'])->update($crud);
        if($update){
          return response()->json(['status'=>true,'message'=>str_replace('_',' ',$data['_name']).' Updated Succes']);
        }
      }
      return response()->json(['status'=>false,'errors'=>$validator->getMessageBag()]);
    }
    public function destroy($data){
      $del= $this->register[$data['_name']]::find($data['id'])->delete();
      if($del){
        return response()->json(['status'=>true,'message'=>str_replace('_',' ',$data['_name']).' Deleted Succes']);
      }
      return response()->json(['status'=>false,'message'=>str_replace('_',' ',$data['_name']).' Failed to Destroy']);
    }
}