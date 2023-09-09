<?php

namespace App\Helpers;


class CrudMaker{

    public function formMaker(array $array=[
        'name'=>'',
        'label'=>'',
        'styles'=>'',
        'classes'=>'',
        'placeholder'=>'',
        'type'=>'text',
        'attribute'=>'',
        'options'=>[]
    ])
    {
        $name = '';
        if(array_key_exists('name', $array)) {
            $name = $array['name'];
        } 
        if(array_key_exists('label', $array)) {
            $label = $array['label'];
        } 
        if(array_key_exists('styles', $array)) {
            $styles = $array['styles'];
        } 
        if(array_key_exists('classes', $array)) {
            $classes = $array['classes'];
        } 
        if(array_key_exists('placeholder', $array)) {
            $placeholder = $array['placeholder'];
        } 
        if(array_key_exists('type', $array)) {
            $type = $array['type'];
        } 
        if(array_key_exists('attribute', $array)) {
            $attribute = $array['attribute'];
        }
        if(array_key_exists('options', $array)) {
            $options = $array['options'];
        } 
          $html='';
          switch ($array) {
            case $array['type']==='text':
                 $html.="
                 <div class='form-group'>
                    <input type='text' style='".$array['styles']."' placeholder='".$array['placeholder']."' ".$array['attribute']." name='".$array['name']."'>
                    <div class='invalid-feedback' id='msg_".$array['name']."'>
                    </div>
                 </div>
                 ";
            break;
            
            default:
                # code...
                break;
          }
        
    }
    
}