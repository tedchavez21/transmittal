<?php

namespace App\Exports;

use App\Models\Record;

class RecordsExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function toCsv()
    {
        $records = $this->query ? $this->query->get() : Record::all();

        $csv = "ID,Encoder,Farmer Name,Province,Municipality,Barangay,Address,Program,Line,Cause of Damage,Mode of Payment,Remarks,Created At\n";

        foreach ($records as $record) {
            $csv .= $record->id . ',' .
                    '"' . str_replace('"', '""', $record->encoderName) . '",' .
                    '"' . str_replace('"', '""', $record->farmerName) . '",' .
                    '"' . str_replace('"', '""', $record->province) . '",' .
                    '"' . str_replace('"', '""', $record->municipality) . '",' .
                    '"' . str_replace('"', '""', $record->barangay) . '",' .
                    '"' . str_replace('"', '""', $record->address) . '",' .
                    '"' . str_replace('"', '""', $record->program) . '",' .
                    '"' . str_replace('"', '""', $record->line) . '",' .
                    '"' . str_replace('"', '""', $record->causeOfDamage) . '",' .
                    '"' . str_replace('"', '""', $record->modeOfPayment) . '",' .
                    '"' . str_replace('"', '""', $record->remarks) . '",' .
                    $record->created_at . "\n";
        }

        return $csv;
    }
}