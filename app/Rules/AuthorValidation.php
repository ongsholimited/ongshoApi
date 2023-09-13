<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Auth;
use App\Models\News\Image;
class AuthorValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $id;
    public function __construct($image_id)
    {
        $this->id=$image_id;
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
        $image=Image::where('author_id',Auth::user()->id)->where('id',$this->id)->first();
        if($image==null)
        {
            return false;
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
        return 'permission not allowed.';
    }
}