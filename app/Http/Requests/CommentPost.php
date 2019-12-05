<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CommentPost extends FormRequest
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
        Validator::extend('uniqueCommentImage', function ($attribute, $value, $parameters, $validator) {
            $count = DB::table('comments')->where('comment', $value)
                                        ->where('image_id', $parameters[0])
                                        ->where('user_id', $parameters[1])
                                        ->count();
            return $count === 0;
        });

        return [
            'id' => 'required|integer|exists:images,id',
            'comment' => 'required|string|uniqueCommentImage:'.$this->id.','.$this->user()->id,
        ];
    }
}
