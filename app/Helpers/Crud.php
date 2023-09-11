<?php
namespace App\Helpers;

class Crud{
    // formname  register with a Model;
    protected $register=[
        'Category'=>'App\Models\News\Category',
    ];
    public function store($data){
        
        try {
            $store=$this->register[$data['form']]::create([
            
            ]);
          }
          
          //catch exception
          catch(\Exception $e) {
            echo 'Message: ' .$e->getMessage();
          }
    }
    public function edit($data){
        
    }
    public function update($data){
        
    }
    public function destroy($data){
        
    }
}