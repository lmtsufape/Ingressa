<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidatoRequest extends FormRequest
{

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('necessidades', 'required|array|max:1', function ($input) {
            return in_array('nenhuma', $input->necessidades);
        });

        return $validator;
    }

    /**
     * Determine if the Candidato is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->role == User::ROLE_ENUM['admin'] || auth()->user()->candidato->id == request()->candidato->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nu_rg' => ['required', 'string'],
            'orgao_expedidor' => ['required', 'string', 'max:5'],
            'uf_rg' => ['required', 'string'],
            'data_expedicao' => ['required', 'date'],
            'titulo' => ['nullable', 'titulo_eleitor'],
            'zona_eleitoral' => ['nullable', 'string'],
            'secao_eleitoral' => ['nullable', 'string'],
            'cidade_natal' => ['required', 'string'],
            'uf_natural' => ['required', 'string'],
            'pais_natural' => ['required', 'string'],
            'estado_civil' => ['required', 'string'],
            'pai' => ['nullable', 'string'],
            'reside' => ['required', 'string'],
            'localidade' => ['required', 'string'],
            'escola_ens_med' => ['required', 'string'],
            'uf_escola' => ['required', 'string'],
            'ano_conclusao' => ['required', 'digits:4'],
            'modalidade' => ['required', 'string'],
            'concluiu_publica' => ['required', 'boolean'],
            'necessidades' => ['required', 'array'],
            'trabalha' => ['required', 'boolean'],
            'grupo_familiar' => ['required', 'numeric'],
            'valor_renda' => ['required', 'numeric'],
            'ds_logradouro' => ['required', 'string'],
            'nu_endereco' => ['required', 'string'],
            'nu_cep' => ['required', 'string'],
            'ds_complemento' => ['nullable', 'string', 'exclude_if:ds_complemento,null'],
            'no_bairro' => ['required', 'string'],
            'no_municipio' => ['required', 'string'],
            'sg_uf_inscrito' => ['required', 'string'],
            'nu_fone1' => ['required', 'string'],
            'nu_fone2' => ['nullable', 'string'],
            'edital' => ['required', 'accepted'],
            'vinculo' => ['required', 'accepted'],
            'tp_sexo' => ['required', 'in:F,M']
        ];
    }

    public function messages()
    {
        return [
            'necessidades.max' => 'Não é permitido selecionar a opção "Nenhuma" e outra.',
            'edital.required' => 'O termo acima é obrigatório.',
            'vinculo.required' => 'O termo acima é obrigatório.'
        ];
    }
}
