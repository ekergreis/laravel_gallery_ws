<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GaleriePost extends FormRequest
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
        $rules = [
            'name' => 'required|string|unique:galeries',
            'descript' => 'string',
            'date_start' => 'date_format:d/m/Y',
            'date_end' => 'date_format:d/m/Y',
            "group" => 'required|min:1',
        ];
        if(!empty($this->request->get('group'))) {
            foreach($this->request->get('group') as $key => $val) {
                $rules['group.'.$key.'.id'] = 'required|integer|exists:groups,id';
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.unique' => __('gallery.galerie.doublon'),
            'name.*' => __('gallery.galerie.invalid_name'),
            'group.*.id.*' => __('gallery.group.id_fail'),
        ];
    }
}
