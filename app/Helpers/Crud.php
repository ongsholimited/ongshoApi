<?php
namespace App\Helpers;

use Validator;
use Auth;
class Crud
{
    // formname  register with a Model;
    protected $register = [
        'Category' => 'App\Models\News\Category',
        'Meta_Keyword' => 'App\Models\News\MetaKeyword',
        'News_Setting' => 'App\Models\News\NewsSetting',
        'Sms_Api' => 'App\Models\SmsApi',
        'Sms_Otp_Template' => 'App\Models\OtpSmsTemplate',
    ];
    protected $setting = [
            "Category" => [
                'delete' => false,
                'addFields' => [
                    'slug' =>'$crud["slug"]= \Str::slug($data["name"],"-");
                              $crud["author_id"]=Auth::user()->id;',
                ],
            ],
            "Sms_Api"=>[
                'delete' => false,
                'addFields' => [
                    'short_code' =>'$crud["short_code"]= explode("|",$data["short_name"])[1];
                                    $crud["short_name"]= explode("|",$data["short_name"])[0];',
                ],
            ],
            "Sms_Otp_Template"=>[
                'delete' => false,
                'addFields' => [
                    'author_id' =>'$crud["author_id"]= auth()->user()->id;',
                ],
            ],
        ];
    
    protected $validation = [
        'Meta_Keyword' => [
            'title' => 'required|max:120',
            'slug' => 'required|max:120',
            'description' => 'nullable|max:120',
            'keyword' => 'nullable|max:120',
            'robots' => 'nullable|max:120',
        ],
        'Category' => [
            'name' => 'required|max:120',
            'description' => 'nullable|max:120',
            'keyword' => 'nullable|max:120',
        ],
        'News_Setting' => [],
        'Sms_Api' => [
            'short_name' => 'required|max:120',
            'api_no' => 'required|max:120',
        ],
        'Sms_Otp_Template' => [
            'short_name' => 'required|max:120',
            'sms' => 'required|min:1',
        ],
    ];
    public function store($data)
    {
// return $data;
// $this->register[$data['_name']];
        $crud = $data;
        unset($crud['_name']);
        if(isset($this->setting[$data['_name']]['addFields'])){
          foreach($this->setting[$data['_name']]['addFields'] as $fields){
            eval($fields);
          }
        }
        // return $crud;
        $validator = Validator::make($crud, $this->validation[$data['_name']]);
        if ($validator->passes()) {
            $store = $this->register[$data['_name']]::create($crud);
            if ($store) {
                return response()->json(['status' => true, 'message' => str_replace('_', ' ', $data['_name']) . ' Added Succes']);
            }
        }
        return response()->json(['status' => false, 'errors' => $validator->getMessageBag()]);
    }
    public function edit($data)
    {
        return $this->register[$data['_name']]::find($data['id']);
    }
    public function update($data)
    {
        $crud = $data;
        unset($crud['_name']);
        unset($crud['form_data_id']);
        if(isset($this->setting[$data['_name']]['addFields'])){
          foreach($this->setting[$data['_name']]['addFields'] as $fields){
            eval($fields);
          }
        }
        $validator = Validator::make($crud, $this->validation[$data['_name']]);

        if ($validator->passes()) {
            $update = $this->register[$data['_name']]::where('id', $data['form_data_id'])->update($crud);
            if ($update) {
                return response()->json(['status' => true, 'message' => str_replace('_', ' ', $data['_name']) . ' Updated Succes']);
            }
        }
        return response()->json(['status' => false, 'errors' => $validator->getMessageBag()]);
    }
    public function destroy($data)
    {
        $del = $this->register[$data['_name']]::find($data['id'])->delete();
        if ($del) {
            return response()->json(['status' => true, 'message' => str_replace('_', ' ', $data['_name']) . ' Deleted Succes']);
        }
        return response()->json(['status' => false, 'message' => str_replace('_', ' ', $data['_name']) . ' Failed to Destroy']);
    }
}
