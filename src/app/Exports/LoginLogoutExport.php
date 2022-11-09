<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LoginLogoutExport extends DefaultValueBinder implements
    WithColumnFormatting,
    ShouldAutoSize,
    WithCustomValueBinder,
    FromCollection,
    WithHeadings
{
    public function __construct(Collection $year)
    {
        $this->year = $year;
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Login',
            'Status',
            'Data de cadastro',
            'Data de Inativação',
            'Perfil',
            'Assessoria',
            'Data',
            'Login',
            'Logout',
            'Total de tempo logado',
            'Dias logados',
            'Responsável pelo cadastro',
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            // 'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function collection()
    {
        return $this->year;
    }
}
