<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class SisuGestaoExport implements FromCollection, WithCustomCsvSettings, WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $candidatos;

    public function __construct($candidatos)
    {

        $this->candidatos = $candidatos;
    }

    public function collection()
    {
        return $this->candidatos;
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
