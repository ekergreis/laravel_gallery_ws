<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalerieGet extends FormRequest
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
            'id' => 'required_without:img|integer|exists:galeries,id',
            'img.*.id' => 'required_without:id|integer',
        ];
    }

    public function messages()
    {
        return [
            'id.*' => __('gallery.galerie.id_fail'),
            'img.*.id.*' => __('gallery.image.id_fail'),
        ];
    }
}
