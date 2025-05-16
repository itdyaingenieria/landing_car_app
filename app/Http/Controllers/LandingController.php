<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Winner;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $winner = Winner::with(['participant.department', 'participant.city'])->latest()->first();

        return Inertia::render('Landing', [
            'departments' => $departments,
            'winner' => $winner ? $winner->participant : null,
        ]);
    }

    public function admin()
    {
        return Inertia::render('Admin/Dashboard');
    }
}
