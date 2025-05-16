<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Department;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function departments()
    {
        $departments = Department::all();
        return response()->json($departments);
    }

    public function cities(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);

        $cities = City::where('department_id', $request->department_id)->get();
        return response()->json($cities);
    }
}
