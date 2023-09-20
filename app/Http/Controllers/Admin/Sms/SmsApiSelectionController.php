<?php

namespace App\Http\Controllers\Admin\Sms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SmsConstant;
use App\Models\SmsApi;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberFormat;
class SmsApiSelectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(){
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $allCountries = $phoneNumberUtil->getSupportedRegions();
        $arr=[];
        foreach ($allCountries as $countryCode) {
            // Get the country's short name (e.g., US for United States)
            $countryShortName = $countryCode;
        
            // Get the country's country code (e.g., 1 for United States)
            $countryCode = $phoneNumberUtil->getCountryCodeForRegion($countryCode);
        
            // Print the country short name and country code
            $arr[]= [$countryShortName=> $countryShortName.'|'.$countryCode];
        }
        return $arr;
        $data=
        [
           'form'=>[
            'name'=>'Sms_Api',
           ],
           'datatable'=>[
            'short_name',
            'short_code',
            'action',
           ],
           'route'=>route('sms.smsapi'),
           'fields'=> [
                [
                    'name'=>'api_no',
                    'label'=>'Api ',
                    'placeholder'=>'Select Key Name',
                    'type'=>'select',
                    'classes'=>'form-control',
                    'options'=>[
                        SmsConstant::API,
                    ]
                ],
                [
                    'name'=>'short_name',
                    'label'=>'Short Name',
                    'placeholder'=>'Select Key Name',
                    'type'=>'text',
                    'classes'=>'form-control',
                ],
                [
                    'name'=>'short_code',
                    'label'=>'Short Code',
                    'placeholder'=>'Select Key Name',
                    'type'=>'text',
                    'classes'=>'form-control',
                ],
            ]
        ];
        // return $get=Category::with('parent')->get();
        if(request()->ajax()){
            $get=SmsApi::query();
            return DataTables::of($get)
            ->addIndexColumn()
            ->addColumn('action',function($get)use($data){
            $button  ='<div class="d-flex justify-content-center">';
            $button.='<a data-url="'.url('crud_maker/edit').'" data-id="'.$get->id.'" data-form="'.$data['form']['name'].'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
            <a data-url="'.url('crud_maker/destroy').'" data-id="'.$get->id.'" data-form="'.$data['form']['name'].'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
            $button.='</div>';
            return $button;
        })
        ->addColumn('key',function($get){
            if($get->key){
                return ucwords(str_replace('_',' ',$get->key));
            }
        })
        ->rawColumns(['action'])->make(true);
        }
        
        return view('crud_maker.crud_maker',compact('data')) ;
    }
}
