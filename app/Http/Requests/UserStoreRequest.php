<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\TipoAnalista;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only admins may create users through this request.
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('isAdmin', User::class);
    }

    /**
     * Prepare and normalize incoming data before validation.
     */
    protected function prepareForValidation()
    {
        // Trim strings
        if ($this->has('name')) {
            $this->merge(['name' => trim($this->input('name'))]);
        }
        if ($this->has('email')) {
            $this->merge(['email' => trim($this->input('email'))]);
        }

        // Ensure arrays are arrays (when sent as comma-separated or single value)
        if ($this->has('tipos_analista') && !is_array($this->tipos_analista)) {
            $val = $this->input('tipos_analista');
            $arr = is_null($val) ? [] : (array) $val;
            $this->merge(['tipos_analista' => array_values(array_filter($arr, fn($v) => $v !== null && $v !== ''))]);
        }

        if ($this->has('cursos_analista') && !is_array($this->cursos_analista)) {
            $val = $this->input('cursos_analista');
            $arr = is_null($val) ? [] : (array) $val;
            $this->merge(['cursos_analista' => array_values(array_filter($arr, fn($v) => $v !== null && $v !== ''))]);
        }

        if ($this->has('cotas_analista') && !is_array($this->cotas_analista)) {
            $val = $this->input('cotas_analista');
            $arr = is_null($val) ? [] : (array) $val;
            $this->merge(['cotas_analista' => array_values(array_filter($arr, fn($v) => $v !== null && $v !== ''))]);
        }

        // Normalize role to the same type as ROLE_ENUM values (usually int)
        if ($this->has('role')) {
            $role = $this->input('role');
            if (is_numeric($role)) {
                $this->merge(['role' => (int) $role]);
            }
        }
    }

    /**
     * Validation rules for creating a user (storeUser flow).
     */
    public function rules()
    {
        $validRoles = array_values(User::ROLE_ENUM);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in($validRoles)],

            // Analyst-related optional fields
            'tipos_analista' => ['nullable', 'array'],
            'tipos_analista.*' => ['nullable', 'exists:tipo_analistas,id'],

            'cursos_analista' => ['nullable', 'array'],
            'cursos_analista.*' => ['nullable', 'exists:cursos,cod_curso'],

            'cotas_analista' => ['nullable', 'array'],
            'cotas_analista.*' => ['nullable', 'exists:cotas,id'],
        ];
    }

    /**
     * Custom attribute names for clearer error messages.
     */
    public function attributes()
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'password_confirmation' => 'confirmação de senha',
            'role' => 'função',
            'tipos_analista' => 'cargo(s)',
            'cursos_analista' => 'curso(s)',
            'cotas_analista' => 'cota(s)',
        ];
    }

    /**
     * Custom messages (Portuguese friendly)
     */
    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O nome deve ter no máximo :max caracteres.',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',

            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'password.confirmed' => 'A confirmação de senha não confere.',

            'role.required' => 'Escolha a função do usuário.',
            'role.in' => 'Função inválida.',

            'tipos_analista.array' => 'Formato inválido para cargos.',
            'tipos_analista.*.exists' => 'Cargo selecionado inválido.',

            'cursos_analista.array' => 'Formato inválido para cursos.',
            'cursos_analista.*.exists' => 'Curso selecionado inválido.',

            'cotas_analista.array' => 'Formato inválido para cotas.',
            'cotas_analista.*.exists' => 'Cota selecionada inválida.',
            'cotas_analista.required' => 'Selecione ao menos uma cota quando o cargo exigir.',
        ];
    }

    /**
     * Conditional validation: require cotas when selected tipos include a 'geral' tipo analista.
     */
    public function withValidator($validator)
    {
        $validator->sometimes('cotas_analista', 'required', function ($input) {
            $selected = $input->tipos_analista ?? [];
            if (empty($selected)) {
                return false;
            }

            $geralIds = TipoAnalista::where('tipo', TipoAnalista::TIPO_ENUM['geral'])->pluck('id')->toArray();

            return count(array_intersect($geralIds, (array) $selected)) > 0;
        });
    }
}