<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchInteraccionRequest extends FormRequest
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
            "fecha" => "required|date|date_format:Y-m-d",
            "dias" => "nullable|numeric|max:15",
            "distancia" =>"required|numeric|max:50",
            "tiempo" => "required|date_format:H:i"
        ];
    }
}
