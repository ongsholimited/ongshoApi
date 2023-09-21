<?php

namespace App\Http\Controllers\Admin\Sms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OtpSmsTemplate;
use libphonenumber\PhoneNumberUtil;
use DataTables;
class AddSmsTemplateController extends Controller
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
            $arr[$countryShortName]= $countryShortName;
        }
    $data=
        [
           'form'=>[
            'name'=>'Sms_Otp_Template',
           ],
           'datatable'=>[
            'short_name',
            'sms',
            'action',
           ],
           'route'=>route('sms.otp_sms_temlate'),
           'fields'=> [
                [
                    'name'=>'sms',
                    'label'=>'SMS (replace @otp_code@)',
                    'placeholder'=>'Write Sms',
                    'type'=>'textarea',
                    'classes'=>'form-control',
                ],
                [
                    'name'=>'short_name',
                    'label'=>'Short Name',
                    'placeholder'=>'Select Country Short Name',
                    'type'=>'select',
                    'classes'=>'form-control',
                    'options'=>$arr
                ],
            ]
        ];
        // return $get=Category::with('parent')->get();
        if(request()->ajax()){
            $get=OtpSmsTemplate::query();
            return DataTables::of($get)
            ->addIndexColumn()
            ->addColumn('action',function($get)use($data){
            $button  ='<div class="d-flex justify-content-center">';
            $button.='<a data-url="'.url('crud_maker/edit').'" data-id="'.$get->id.'" data-form="'.$data['form']['name'].'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
            <a data-url="'.url('crud_maker/destroy').'" data-id="'.$get->id.'" data-form="'.$data['form']['name'].'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
            $button.='</div>';
            return $button;
        })
        ->rawColumns(['action'])->make(true);
        }
        
        return view('crud_maker.crud_maker',compact('data')) ;
    }
}
