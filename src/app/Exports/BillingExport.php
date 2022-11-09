<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BillingExport extends DefaultValueBinder implements
    WithColumnFormatting,
    ShouldAutoSize,
    WithCustomValueBinder,
    FromCollection,
    WithHeadings,
    WithMapping
{
    public function __construct(Collection $year)
    {
        $this->year = $year;
    }

    public function headings(): array
    {
        return [
            'Assessoria',
            'Mês',
            'Ativos',
            'Inativos',
            'Total',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice['company'],
            $invoice['month'],
            $invoice['active'],
            $invoice['inactive'],
            $invoice['total'],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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
