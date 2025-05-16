<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantsExport;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = Participant::with(['department', 'city'])->get();
        return response()->json($participants);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|regex:/^[\p{L} ]+$/u',
            'last_name'         => 'required|regex:/^[\p{L} ]+$/u',
            'document_number'   => 'required|numeric|unique:participants',
            'department_id'     => 'required|exists:departments,id',
            'city_id'           => 'required|exists:cities,id',
            'phone'             => 'required|numeric',
            'email'             => 'required|email|unique:participants',
            'habeas_data'       => 'required|boolean|accepted',
        ], [
            'first_name.required'      => 'El nombre es obligatorio.',
            'first_name.regex'         => 'El nombre solo puede contener letras y espacios.',
            'last_name.required'       => 'El apellido es obligatorio.',
            'last_name.regex'          => 'El apellido solo puede contener letras y espacios.',
            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.numeric'  => 'El número de documento debe ser numérico.',
            'document_number.unique'   => 'El número de documento ya está registrado.',
            'department_id.required'   => 'El departamento es obligatorio.',
            'department_id.exists'     => 'El departamento seleccionado no es válido.',
            'city_id.required'         => 'La ciudad es obligatoria.',
            'city_id.exists'           => 'La ciudad seleccionada no es válida.',
            'phone.required'           => 'El teléfono es obligatorio.',
            'phone.numeric'            => 'El teléfono debe ser numérico.',
            'email.required'           => 'El correo electrónico es obligatorio.',
            'email.email'              => 'El correo electrónico no es válido.',
            'email.unique'             => 'El correo electrónico ya está registrado.',
            'habeas_data.required'     => 'La aceptación del habeas data es obligatoria.',
            'habeas_data.boolean'      => 'La aceptación del habeas data debe ser verdadero o falso.',
            'habeas_data.accepted'     => 'Debes aceptar el habeas data para continuar.'
        ]);



        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $participant = Participant::create($request->all());

        return response()->json([
            'message' => 'Participante registrado exitosamente',
            'participant' => $participant
        ], 201);
    }

    public function drawWinner()
    {
        // Check if there are at least 5 participants
        $participantsCount = Participant::count();

        if ($participantsCount < 5) {
            return response()->json([
                'message' => 'Se necesitan al menos 5 participantes para seleccionar un ganador',
                'participants_count' => $participantsCount
            ], 400);
        }

        // Check if there's already a winner
        $existingWinner = Winner::latest()->first();

        // Select a random participant
        $winner = Participant::inRandomOrder()->first();

        // Create or update the winner record
        if ($existingWinner) {
            $existingWinner->update(['participant_id' => $winner->id]);
        } else {
            Winner::create(['participant_id' => $winner->id]);
        }

        return response()->json([
            'message' => 'Ganador seleccionado exitosamente',
            'winner' => $winner
        ]);
    }

    public function getWinner()
    {
        $winner = Winner::with(['participant.department', 'participant.city'])->latest()->first();

        if (!$winner) {
            return response()->json([
                'message' => 'Aún no se ha seleccionado un ganador'
            ], 404);
        }

        return response()->json([
            'winner' => $winner->participant
        ]);
    }

    public function export()
    {
        return Excel::download(new ParticipantsExport, 'participantes.xlsx');
    }
}
