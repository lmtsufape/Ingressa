<?php

namespace App\Exports;

use App\Models\Inscricao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InscritosExport implements FromQuery, WithHeadings, WithChunkReading
{

    public function __construct(private Builder $query){}


   public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Nome Social',
            'Data de Nascimento',
            'Sexo',
            'Estado Civil',
            'CPF',
            'RG', 'Orgão expedidor', 'UF_RG', 'Data de expedição',
            'Nº do título', 'zona', 'seção',
            'Naturalidade', 'UF_NATURALIDADE', 'País',
            'Nome da mãe',
            'Nome do pai',
            'Unidade',
            'Formação',
            'Turno',
            'Forma de ingresso',
            'Ano de ingresso',
            'Semestre de entrada',
            'Tipo de chamada',
            'Nota em linguagens',
            'Nota em ciências humanas',
            'Nota em ciências da natureza',
            'Nota em Exatas',
            'Nota na redação',
            'Média das notas - SISU',
            'Curso',
            'Modalidade escolhida',
            'Modalidade ocupada',
            'Endereço', 'nº', 'CEP', 'complemento', 'cidade', 'bairro', 'UF_ENDEREÇO',
            'Celular I',
            'Celular II',
            'Contato de emergência',
            'Email',
            'Estabelecimento que concluiu o Ensino Médio',
            'UF_ESCOLA_EM',
            'Ano de Conclusão',
            'Modalidade',
            'Concluiu o Ensino Médio na rede pública?',
            'Concluiu o Ensino Médio em escolas comunitárias do campo conveniadas?',
            'Necessidades Específicas',
            'Cor/Raça',
            'Quilombola (sim ou não)',
            'Indígena (sim ou não)',
            'Local de moradia atual',
            'Seu local de moradia atual se encontra em:',
            'Exerce atividade remunerada?',
            'Qtde de pessoas no grupo familiar',
            'Renda total',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
