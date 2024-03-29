<?php


use App\Http\Livewire\Companies\CompanyIndex;
use App\Http\Livewire\Conditions\ConditionIndexCompany;
use App\Http\Livewire\Contracts\ContractIndexCustomer;
use App\Http\Livewire\Customers\CustomerIndexCompany;
use App\Http\Livewire\Products\ProductIndexCompany;
use App\Http\Livewire\Quotes\QuoteIndexContract;
use App\Http\Livewire\Roles\RoleIndexCompany;
use App\Http\Livewire\Services\ServiceIndexCompany;
use App\Http\Livewire\Users\UserIndexCompany;
use App\Models\Permission;
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

Route::get('/permissions', function () {
    $user = User::find(1);
    $permissions = Permission::all();

    foreach ($permissions as $key => $permission) {
        
        $user->givePermissionTo($permissions[18]->name);
    }
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        
        Route::get('/dashboard', function () {
        
         return view('dashboard');
        
        })->name('dashboard');

        // Users - Roles and Permission Modules
        Route::get('/users-index-company/{id?}', UserIndexCompany::class)->name('users.index.company');
        Route::get('/roles-index-company/{id?}', RoleIndexCompany::class)->name('roles.index.company');
        
        // Operationals Modules 
        Route::get('/companies-index', CompanyIndex::class)->name('companies.index');
        Route::get('/contracts-index-customer/{customer_id?}/{company_id?}', ContractIndexCustomer::class)->name('contracts.index.customer');  
        Route::get('/customers-index-company/{id?}', CustomerIndexCompany::class)->name('customers.index.company');   
        Route::get('/services-index-company/{id?}', ServiceIndexCompany::class)->name('services.index.company');  
        Route::get('/products-index-company/{id?}', ProductIndexCompany::class)->name('products.index.company');  
        Route::get('/conditions-index-company/{id?}', ConditionIndexCompany::class)->name('conditions.index.company');  
        Route::get('/quotes-index-contract/{id?}', QuoteIndexContract::class)->name('quotes.index.contract');   
    });
