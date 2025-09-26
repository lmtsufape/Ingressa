<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class CotaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->role == User::ROLE_ENUM['admin'];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cota' => 'nullable',
            'nome' => 'required|string|max:1000',
            'cod_novo' => 'required|string|min:2|max:255',
            'cod_siga' => 'required|string|min:2|max:255',
            'descricao' => 'required|string|min:10|max:1000',
            'cursos.*'    => 'nullable',
        ];
    }
}
