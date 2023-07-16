<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Otp;
class OtpValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $code;
    protected $message;
    public function __construct($code)
    {
        $this->code=$code;
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
        $result = Otp::validate('mobile:'.$value,$this->code);
        // return $result->status;
        $condition=true;
        if($result->status==false){
            $this->message='the otp code is invalid';
            $condition= false;
        }
        $phone=Phone::where('user_id',auth()->user()->id)->count();
        if ($phone>=5) {
            $this->message='Max Phone Number added Already';
            $condition=false;
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
