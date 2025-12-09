<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    //
    public function show()
    {
      return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(Request $request)
    {
       Log::info($request);
       $validated=$request->validate(
        [
          'fname'
        ]
       );
    //    Employee::create([

    //    ]);
    }
}
