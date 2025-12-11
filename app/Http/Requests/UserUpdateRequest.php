<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\TipoAnalista;

class UserUpdateRequest extends FormRequest
{
    /**
     * Only admins may update users via this request.
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('isAdmin', User::class);
    }

    /**
     * Normalize incoming data before validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge(['name' => trim($this->input('name'))]);
        }
        if ($this->has('email')) {
            $this->merge(['email' => trim($this->input('email'))]);
        }

        if ($this->has('tipos_analista_edit') && !is_array($this->tipos_analista_edit)) {
            $val = $this->input('tipos_analista_edit');
            $arr = is_null($val) ? [] : (array) $val;
            $this->merge(['tipos_analista_edit' => array_values(array_filter($arr, fn($v) => $v !== null && $v !== ''))]);
        }

        if ($this->has('cursos_analista_edit') && !is_array($this->cursos_analista_edit)) {
            $val = $this->input('cursos_analista_edit');
            $arr = is_null($val) ? [] : (array) $val;
            $this->merge(['cursos_analista_edit' => array_values(array_filter($arr, fn($v) => $v !== null && $v !== ''))]);
        }

        if ($this->has('cotas_analista_edit') && !is_array($this->cotas_analista_edit)) {
            $val = $this->input('cotas_analista_edit');
            $arr = is_null($val) ? [] : (array) $val;
            $this->merge(['cotas_analista_edit' => array_values(array_filter($arr, fn($v) => $v !== null && $v !== ''))]);
        }

        if ($this->has('role')) {
            $role = $this->input('role');
            if (is_numeric($role)) {
                $this->merge(['role' => (int) $role]);
            }
        }
    }

    public function rules()
    {
        $userId = $this->input('user_id');
        $user = $userId ? User::find($userId) : null;

        $validRoles = array_values(User::ROLE_ENUM);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user ? $user->id : null)],

            'tipos_analista_edit' => ['nullable', 'array'],
            'tipos_analista_edit.*' => ['nullable', 'exists:tipo_analistas,id'],

            'cotas_analista_edit' => ['nullable', 'array'],
            'cotas_analista_edit.*' => ['nullable', 'exists:cotas,id'],

            'cursos_analista_edit' => ['nullable', 'array'],
            'cursos_analista_edit.*' => ['nullable', 'exists:cursos,cod_curso'],

            'role' => ['nullable', Rule::in($validRoles)],
        ];
    }

    /**
     * Add conditional rules (cotas required when tipos include 'geral').
     */
    public function withValidator($validator)
    {
        $validator->sometimes('cotas_analista_edit', 'required', function ($input) {
        $selected = $input->tipos_analista_edit ?? [];
        if (empty($selected)) {
            return false;
        }

        $geralIds = TipoAnalista::where('tipo', TipoAnalista::TIPO_ENUM['geral'])
                        ->pluck('id')
                        ->toArray();

        return count(array_intersect($geralIds, (array) $selected)) > 0;
        });
    }

    public function attributes()
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'role' => 'função',
            'tipos_analista_edit' => 'cargo(s)',
            'cursos_analista_edit' => 'curso(s)',
            'cotas_analista_edit' => 'cota(s)',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O nome deve ter no máximo :max caracteres.',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',

            'role.in' => 'Função inválida.',

            'tipos_analista_edit.array' => 'Formato inválido para cargos.',
            'tipos_analista_edit.*.exists' => 'Cargo selecionado inválido.',

            'cursos_analista_edit.array' => 'Formato inválido para cursos.',
            'cursos_analista_edit.*.exists' => 'Curso selecionado inválido.',

            'cotas_analista_edit.array' => 'Formato inválido para cotas.',
            'cotas_analista_edit.*.exists' => 'Cota selecionada inválida.',
            'cotas_analista_edit.required' => 'Selecione ao menos uma cota quando o cargo exigir.',
        ];
    }
}

