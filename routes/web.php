<?php

use App\Http\Livewire\CompanyCreate;
use App\Http\Livewire\CompanyIndex;
use App\Models\User;
use Illuminate\Auth\Middleware\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    
    Route::get('/dashboard', function () {
    
     return view('dashboard');
    
    })->name('dashboard');

    

    Route::get('/company-index', CompanyIndex::class)->name('company.index');
    Route::get('/company-create', CompanyCreate::class)->name('company.create');
});
