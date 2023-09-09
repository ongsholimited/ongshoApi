<?php

namespace App\Helpers;


class CrudMaker{



    public function formMaker(array $array=[
        [
            'name'=>'Demo Input',
            'styles'=>'',
            'classes'=>'',
            'placeholder'=>'',
            'type'=>'text',
            'attribute'=>'',
            'options'=>[]
        ]
    ])
    {
       foreach($array as $field){
          $html='';
          switch ($field) {
            case $field['type']==='text':
                 $html.="
                 <input type='text' style='".$field['styles']."' placeholder='".$field['placeholder']."' ".$field['attribute'].">
                 ";
                break;
            
            default:
                # code...
                break;
          }
       }
        
    }
    
}