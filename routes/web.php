<?php




use App\Http\Livewire\Companies\CompanyIndex;
use App\Http\Livewire\Users\UserIndex;
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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        
        Route::get('/dashboard', function () {
        
         return view('dashboard');
        
        })->name('dashboard');

        // Users - Roles and Permission Modules
        Route::get('/users-index', UserIndex::class)->name('users.index');
        //Route::get('/roles-index', RoleIndex::class)->name('roles.index');
        //Route::get('/permissions-index', PermissionIndex::class)->name('permissions.index');

        // Operationals Modules 
        Route::get('/companies-index', CompanyIndex::class)->name('companies.index');
        //Route::get('/boxes-index', BoxIndex::class)->name('box.index');
    });
