<?php
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MovieController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('Employees',[EmployeeController::class,'show'])->middleware(['auth', 'verified'])->name('employee');

Route::post('AddEmployee',[EmployeeController::class,'store'])->middleware(['auth', 'verified'])->name('employee.add');
Route::put('UpdateEmployee/{id}',[EmployeeController::class,'update'])->middleware(['auth', 'verified'])->name('employee.update');
Route::delete('DeleteEmployee/{id}',[EmployeeController::class,'delete'])->middleware(['auth', 'verified'])->name('employee.delete');


Route::get('/browse_movies/', [MovieController::class, 'show'])->middleware(['auth', 'verified'])->name('movies.browse');
Route::get('/documents/{id}', function ($id) {
   $bucket = DB::connection('mongodb')->getMongoDB()->selectGridFSBucket();
        // Open the stream for reading the file
        $stream = $bucket->openDownloadStream(new ObjectId($id));

        // Read the stream contents
        $contents = stream_get_contents($stream);
        fclose($stream);

        // Optionally, retrieve metadata like the original filename
        // $fileMetaData = $bucket->findOne(['_id' => $fileId]);

        return Response::make($contents, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="employee-signature.pdf"',
        ]);
})->middleware(['auth', 'verified'])->name('show.document');
require __DIR__.'/settings.php';
