<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\EmployeeController;

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
require __DIR__.'/settings.php';
