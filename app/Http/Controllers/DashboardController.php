<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Winner;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get all participants with their related department and city
            $participants = Participant::with(['department', 'city'])->orderBy('created_at', 'desc')->get();

            // Get the latest winner with their related participant
            $winner = Winner::with(['participant.department', 'participant.city'])->latest()->first();

            // Calculate statistics
            $totalParticipants = $participants->count();
            $todayParticipants = $participants->filter(function ($p) {
                return Carbon::parse($p->created_at)->isToday();
            })->count();

            return Inertia::render('DashboardNew', [
                'participants'  => $participants,
                'winner'        => $winner ? $winner->participant : null,
                'stats'         => [
                    'total' => $totalParticipants,
                    'today' => $todayParticipants,
                    'canDrawWinner' => $totalParticipants >= 5
                ]
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions initializing the dashboard with cero participants
            return Inertia::render('DashboardNew', [
                'participants'  => [],
                'winner'        => null,
                'stats'         => [
                    'total' => 0,
                    'today' => 0,
                    'canDrawWinner' => false
                ],
                'error' => $e->getMessage()
            ]);
        }
    }
}
