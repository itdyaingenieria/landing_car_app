<?php

namespace App\Exports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Participant::with(['department', 'city'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Apellido',
            'Cédula',
            'Departamento',
            'Ciudad',
            'Teléfono',
            'Email',
            'Aceptó Habeas Data',
            'Fecha de Registro'
        ];
    }

    public function map($participant): array
    {
        return [
            $participant->id,
            $participant->first_name,
            $participant->last_name,
            $participant->document_number,
            $participant->department->name,
            $participant->city->name,
            $participant->phone,
            $participant->email,
            $participant->habeas_data ? 'Sí' : 'No',
            $participant->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
