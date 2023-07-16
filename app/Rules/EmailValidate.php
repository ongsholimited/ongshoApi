<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Otp;
use App\Models\User;
class EmailValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $email;
    protected $message;
    public function __construct($email)
    {
        $this->email=$email;
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
        
        $result = Otp::validate('email:'.$this->email,$value);
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
