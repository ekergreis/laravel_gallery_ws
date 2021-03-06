<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupPost extends FormRequest
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
        return [
            'id' => 'required_without:name|integer|exists:groups,id',
            'name' => 'required_without:id|string|unique:groups'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => __('gallery.group.doublon'),
            'name.*' => __('gallery.group.invalid_name'),
            'id.*' => __('gallery.group.id_fail'),
        ];
    }
}
