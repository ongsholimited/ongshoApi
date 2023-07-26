<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;
class OtpTokenCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $email;
    public $token_type;
    public function __construct($email,$token_type)
    {
        $this->email=$email;
        $this->token_type=$token_type;
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
        if($this->token_type=='password_change'){
            $key='pass:'.$this->email.':'.$value;
        }
        if($this->token_type=='email'){
            $key='email:'.$this->email.':'.$value;
        }
        $hasValue=Cache::store('database')->get($key);
        if($hasValue){
            Cache::forget($key);
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'something went wrong.';
    }
}
