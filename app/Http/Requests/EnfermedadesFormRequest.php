<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnfermedadesFormRequest extends FormRequest
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
            'animal'=>'required',
            'estado'=>'required',
            'enfermedad'=>'required',
            'fecha'=>'required',
            'fecha_tratamiento'=>'required_if:estado,Tratado',
            'tratamiento'=>'required_if:estado,Tratado',
            'enfermedades_nombre'=>'required_if:enfermedad,nueva',
        ];
    }
}

