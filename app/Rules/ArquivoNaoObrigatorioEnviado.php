<?php

namespace App\Rules;

use App\Models\Inscricao;
use Illuminate\Contracts\Validation\ImplicitRule;

class ArquivoNaoObrigatorioEnviado implements ImplicitRule
{

    public $inscricao;
    public $documento;
    public $declaracao;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Inscricao $inscricao, $documento, $declaracao)
    {
        $this->inscricao = $inscricao;
        $this->documento = $documento;
        $this->declaracao = $declaracao;
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
        return $this->inscricao->isArquivoEnviado($this->documento) || $this->declaracao == "true";
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute é obrigatório quando não marcar o termo de compromisso para entregar o documento na primeira semana de aula.';
    }
}
