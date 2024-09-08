<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EventImport implements ToArray, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function array(array $rows)
    {
        foreach ($rows as $row) {

            if ($this->isRowEmpty($row)) {
                continue;
            }
        }
    }

    private function isRowEmpty(array $row): bool
    {
        // Check if all columns are NULL or empty
        return empty(array_filter($row, function ($value) {
            return $value !== null && $value !== '';
        }));
    }
}
