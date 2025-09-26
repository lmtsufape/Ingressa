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
            'RG', 'Orgão expedidor', 'UF', 'Data de expedição',
            'Nº do título', 'zona', 'seção',
            'Naturalidade', 'UF', 'País',
            'Nome da mãe',
            'Nome do pai',
            'Unidade',
            'Formação',
            'Turno',
            'Forma de ingresso',
            'Ano de ingresso',
            'Nota',
            'Curso',
            'Modalidade escolhida',
            'Modalidade ocupada',
            'Endereço', 'nº', 'CEP', 'complemento', 'cidade', 'bairro', 'UF',
            'Celular I',
            'Celular II',
            'Contato de emergência',
            'Email',
            'Estabelecimento que concluiu o Ensino Médio',
            'UF',
            'Ano de Conclusão',
            'Modalidade',
            'Concluiu o Ensino Médio na rede pública?',
            'Concluiu o Ensino Médio em escolas comunitárias do campo conveniadas?',
            'Necessidades Especiais',
            'Cor/Raça',
            'Quilombola (sim ou não)',
            'Indígena (sim ou não)',
            'Local de moradia atual',
            'Exerce atividade remunerada?',
            'Qtde de pessoas no grupo familiar',
            'Renda per capita',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
