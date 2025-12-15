<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use Inertia\Inertia;
use MongoDB\Client;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
      /** 🔗 Mongo connection */
    $client = new Client(config('database.connections.mongodb.dsn'));
    $db = $client->selectDatabase(
        config('database.connections.mongodb.database')
    );

    /** 📦 GridFS bucket */
    $bucket = $db->selectGridFSBucket();

    /** 📄 Upload file */
    $stream = fopen($request->file('files')->getRealPath(), 'rb');

    $fileId = $bucket->uploadFromStream(
        $request->file('files')->getClientOriginalName(),
        $stream,
        [
            'metadata' => [
                'type' => 'employee_document'
            ]
        ]
    );
    Log::info("Uploaded file with ID: " . $fileId);
    //    $validated=$request->validate(
    //     [
    //       'fname'=>'required|string|max:50',
    //       'lname'=>'required|string|max:50',
    //       'dob'=>'required|date',
    //       'position'=>'required|string|max:50',

    //     ]
    //    );
    //   Employee::create([
    //      'fname'=>$validated['fname'],
    //      'lname'=>$validated['lname'],
    //      'dob'=>$validated['dob'],
    //      'position'=>$validated['position'],
    //     '$emp_created' => $request->emp_created,

    //   ]);


    }
    public function update(Request $request)
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
      $Employee = Employee::findOrFail($request->route('id'));
        $Employee->update($validated);

    }

    public function delete(Request $request)
    {
       $Employee = Employee::findOrFail($request->route('id'));
       $Employee->delete();
    }
}
