<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCandidatoRequest extends FormRequest
{

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('necessidades', 'required|array|max:1', function ($input) {
            return in_array('nenhuma', $input->necessidades ?? []);
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
            'no_social' => ['nullable', 'string'],
            'requerimento_nome_social' => ['required_with:no_social', 'file', 'mimes:pdf', 'max:2048'],
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
            'concluiu_comunitaria' => ['required', 'boolean'],
            'necessidades' => ['required', 'array', 'min:1'],
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
            'nu_fone_emergencia' => ['required', 'string'],
            'nome_contato_emergencia' => ['required'],
            'parentesco_contato_emergencia' => ['required'],
            'edital' => ['required', 'accepted'],
            'vinculo' => ['required', 'accepted'],
            'tp_sexo' => ['required', 'in:F,M'],
            'quilombola' => ['required', 'boolean'],
            'indigena' => ['required', 'boolean'],
            'etnia_e_cor' => ['required', 'integer'],
            'dispositivos_moradia'   => ['required', 'array'],
            'dispositivos_moradia.*' => [Rule::in(['banda_larga','internet_movel','smartphone','computador','tablet','nenhuma'])],
            'cadunico' => ['required', 'in:sim,nao'],
            'filhos'   => ['required', 'array'],
            'filhos.*' => [Rule::in(['primeira_infancia','idade_escolar','nao_tenho'])],
            'gestante' => ['required_if:tp_sexo,F'],
            'transgenero' => ['required', 'in:sim,nao,outro,prefiro_nao_responder'],
            'lgbtqiap' =>   ['required', 'in:sim,nao,outro,prefiro_nao_responder'],
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $necessidades = (array) $this->input('necessidades', []);
            if (in_array('nenhuma', $necessidades, true) && count($necessidades) > 1) {
                $validator->errors()->add('necessidades', 'Selecione apenas "Nenhuma" ou uma ou mais deficiências.');
            }

            $moradia = (array) $this->input('dispositivos_moradia', []);
            if (in_array('nenhuma', $moradia, true) && count($moradia) > 1) {
                $validator->errors()->add('dispositivos_moradia', 'Selecione apenas "Não disponho..." ou uma ou mais opções acima.');
            }

            $filhos = (array) $this->input('filhos', []);
            if (in_array('nao_tenho', $filhos, true) && count($filhos) > 1) {
                $validator->errors()->add('filhos', 'Selecione apenas "Não tenho" ou uma das opções "Sim".');
            }
        });
    }
}
