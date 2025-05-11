<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProfessorenController;
use App\Http\Controllers\LaborController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('home');
});

Route::get('/profil', [ProfilController::class, 'show'])->name('profil');

Route::get('/profil/bearbeiten', [ProfilController::class, 'bearbeiten']);
Route::post('/profil/bearbeiten', [ProfilController::class, 'speichern']);

Route::get('/professoren', [ProfessorenController::class, 'profs']);

Route::get('/laborliste', [LaborController::class, 'liste'])->name('laborliste');

Route::get('/', function () {
    return view('welcome');
});
