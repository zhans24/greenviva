<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'phone'   => ['required','string','max:50'],
            'message' => ['nullable','string','max:5000'],
        ]);

        $lead = Lead::create($data);

        return response()->json([
            'ok'   => true,
            'id'   => $lead->id,
            'msg'  => 'Lead accepted',
        ]);
    }
}
