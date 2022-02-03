<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AprovadosExport implements FromCollection, WithCustomCsvSettings, WithHeadings, WithStrictNullComparison
{
    public function headings(): array
    {
        return [
            'CPF',
            'RG',
            'NOMEPESSOA',
            'CODPROGRAMAFORM',
            'SEMESTREADMISSAO',
            'ANOADMISSAO',
            'TURNO',
            'CODMODAL',
            'CODTIPOINGRESSO',
            'NOMEMAE',
            'NOMEPAI',
            'SEXO',
            'NACIONALIDADE',
            'DATANASCIMENTO',
            'ESTADOCIVIL',
            'CIDADENATAL',
            'CEP',
            'NUMENDERECO',
            'COMPENDERECO',
            'DATAEXPEDICAORG',
            'SIGLAORGAOEXP',
            'UFRG',
            'PAISRG',
            'EMAILSEC',
            // 'NUMPASSAPORTE',
            'NOTAENEM',
            // 'INSCRICAOVEST',
            // 'NOTAVEST',
            // 'CLASSVEST',
            'ANOCONCENSMED',
            'TIPOCOTAFINAL',
            'CODPOLO',
            'COR/RACA',
            'TITULOELEITOR',
            'ZONAELEITORAL',
            'SECAOELEITORAL',
            'TELEFONERES',
            'TELEFONECEL',
            'ESCOLAENSMED',
            // 'ESCOLARIDADEPAI',
            // 'ESCOLARIDADEMAE',
            'DEFICIENCIAS',
        ];
    }
    protected $inscricoes;

    public function __construct($inscricoes)
    {
        $this->inscricoes = $inscricoes;
    }

    public function collection()
    {
        return $this->inscricoes;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ";",
            'line_ending' => ";\n",
            'enclosure' => ''
        ];
    }

}
