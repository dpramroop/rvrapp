<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    //
    public function show()
    {
      return Inertia::render('Employee', ['employee'=>Employee::all()

        ]);
    }

    public function store(Request $request)
    {
       Log::info($request);
       $validated=$request->validate(
        [
          'fname'=>'string|max:50',
          'lname'=>'string|max:50',
          'dob'=>'date',
          'position'=>'string|max:50'
        ]
       );
      Employee::create([
         'fname'=>$validated['fname'],
         'lname'=>$validated['lname'],
         'dob'=>$validated['dob'],
         'position'=>$validated['position']
      ]);

    }
}
