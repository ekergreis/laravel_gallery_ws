<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LikePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        Validator::extend('uniqueLike', function ($attribute, $value, $parameters, $validator) {
            if(empty($parameters[0]) && empty($parameters[1])) return false;

            if(!empty($parameters[0]) && !empty($parameters[1]) && $value==$parameters[0]) return true;

            if(empty($parameters[0])) $parameters[0]=null;
            if(empty($parameters[1])) $parameters[1]=null;
            $count = DB::table('likes')->where('comment_id', $parameters[0])
                                        ->where('image_id', $parameters[1])
                                        ->where('user_id', $parameters[2])
                                        ->count();
            return $count === 0;
        });

        return [
            'id_image' => 'integer|nullable|uniqueLike:'.$this->id_comment.','.$this->id_image.','.$this->user()->id,
            'id_comment' => 'integer|nullable|uniqueLike:'.$this->id_comment.','.$this->id_image.','.$this->user()->id
        ];
    }
}
