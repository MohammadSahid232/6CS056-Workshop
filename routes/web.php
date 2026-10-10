<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('students.index');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('home');

Route::resource('students', StudentController::class);
Route::resource('courses', CourseController::class);
