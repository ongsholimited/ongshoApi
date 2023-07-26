<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Otp;
class OtpEmailValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $email;
    protected $message;
    protected $type;
    public function __construct($email,$type)
    {
        $this->email=$email;
        $this->type=$type;
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
        if($this->type=='password_change'){
            $result = Otp::validate('change_pass:'.$this->email,$value);
        }
        if($this->type=='email'){
            $result = Otp::validate('email:'.$this->email,$value);
        }
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
