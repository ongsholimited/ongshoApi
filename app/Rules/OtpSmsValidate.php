<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Otp;
class OtpSmsValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $phone;
    public $key;
    public $message;
    public function __construct($phone,$key)
    {
        $this->phone=$phone;
        $this->key=$key;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
       
       $result = Otp::validate($this->key.':'.$this->phone,$value);
        
        // return $result->status;
        $condition=true;
        if($result->status==false){
            $this->message='the otp code is invalid';
            if($result->error==='expired'){
                $this->message="the otp has been expired";
            }
            $condition= false;
        }
        return $condition;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
