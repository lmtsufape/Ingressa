<?php

namespace App\Rules;

use App\Models\Inscricao;
use Illuminate\Contracts\Validation\ImplicitRule;

class ArquivoEnviado implements ImplicitRule
{

    public $inscricao;
    public $documento;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Inscricao $inscricao, $documento)
    {
        $this->inscricao = $inscricao;
        $this->documento = $documento;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->inscricao->isArquivoEnviado($this->documento);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute é obrigatório.';
    }
}
