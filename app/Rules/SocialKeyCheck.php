<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Otp;
class SocialKeyCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $otp_token;
    public $msg;
    public $number;
    public function __construct($otp_token,$number)
    {
        $this->otp_token=$otp_token;
        $this->number=$number;

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
        $otp_token=$this->otp_token;
        $string = $value;
        $pattern = "/\bphone\b/i";
        if (preg_match($pattern, $string)) {
            if($otp_token!=null or $otp_token!=''){
               $this->msg='otp token required.';
               return false;
            }
           $result= Otp::validate($value.':'.$this->number,$otp_token);
            if($result->status==false){
                $this->msg="otp token don`t match";
            }
            return true;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        return $this->msg;
    }
}
