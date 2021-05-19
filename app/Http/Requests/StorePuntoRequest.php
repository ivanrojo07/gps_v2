<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePuntoRequest extends FormRequest
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
            "usuario_id" => "required|numeric",
            "lat" => "required|numeric",
            "lng" => "required|numeric",
            "fecha" => "required|date|date_format:Y-m-d",
            "hora" => "required|date_format:H:i:s"
        ];
    }
}
