<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImagePost extends FormRequest
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
            'id' => 'required|integer|exists:galeries,id',
            'data' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => __('gallery.galerie.id_fail'),
            'data.*' => __('gallery.image.invalid_data'),
        ];
    }
}
