<?php

namespace App\Http\Livewire\Companies;


use App\Exports\CompaniesExport;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\withMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Spatie\Permission\Exceptions\UnauthorizedException;


class CompanyIndex extends Component
{
    use WithPagination;



    public $deleteCompany = false;
    public $forceDeleteCompany = false;
    public $restoreCompany = false;
    public $companyId; 
    public $password;



    public $createNewCompany = false; 
    

    public $companyEdit;
    public $companyShow;

    
    public $editCompany = false;
    public $showCompany = false;

    public $search; 

    public $name;

    public $active = true;

    public $permissions;


    protected $rules = [
        'name' => 'required|string|max:70|min:1|unique:companies,name',        
    ];


    public function mount()
    {
        
        $this->getPermissions();
       
    }


    public function render()
    {
        $companiesByUser = User::find(auth()->user()->id)->companies();
        if ($this->active == true) {

            $companies = $companiesByUser->Where(function($query) {
                             $query  ->orWhere('companies.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('companies.id', 'DESC')->paginate(10);
        }else{

             $companies = $companiesByUser->Where(function($query) {
                             $query  ->orWhere('companies.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('companies.id', 'DESC')->onlyTrashed()->paginate(10);
                                   
        }
                                
        if(in_array("viewCompanies", $this->permissions)){

            return view('livewire.companies.company-index', [

                'companies' => $companies,

            ]);

        }else{

            throw UnauthorizedException::forPermissions($this->permissions);

        }
        
    }

    public function getPermissions()
    {
        $userWithRolesAndPermissions = User::where('id',auth()->user()->id)->with('roles')->first();
        $userWithDirectsPermissions = User::where('id',auth()->user()->id)->with('permissions')->first();
        
        
        $permissions = [];

        //find permissions for roles
        foreach ($userWithRolesAndPermissions->roles as $key => $role) {
           
            $role = Role::where('id',$role->id)->with('permissions')->first();
                
                foreach ($role->permissions as $key => $permission) {
                    array_push($permissions,$permission->name);
                }                
        }

        //find directs permissions
        foreach ($userWithDirectsPermissions->permissions as $key => $permission) {
        
            array_push($permissions,$permission->name);
                         
        }

        $this->permissions = array_unique($permissions);

        //dd($this->permissions);
    }

    public function updatingSearch()
    {
        $this->resetPage();        
    }

    public function confirmCompanyDeletion($companyId)
    {
        $this->companyId = $companyId; 
        $this->deleteCompany = true;
    }

    public function confirmForceCompanyDeletion($companyId)
    {
        $this->companyId = $companyId; 
        $this->forceDeleteCompany = true;
    }

    public function confirmRestoreCompany($companyId)
    {
        $this->companyId = $companyId; 
        $this->restoreCompany = true;
    }

    public function deleteCompany()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            Company::destroy($this->companyId);
            $this->deleteCompany = false;
            $this->password = null;
            $this->companyId = null;
            $this->emit("deleted");

        }       
    }

    public function forceDeleteCompany()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $company = Company::withTrashed()->find($this->companyId);
            $company->forceDelete();
            $this->forceDeleteCompany = false;
            $this->password = null;
            $this->companyId = null;
            $this->emit("forceDeleted");

        }       
    }

    public function restoreCompany()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $company = Company::withTrashed()->find($this->companyId);
            $company->restore();
            $this->restoreCompany = false;
            $this->password = null;
            $this->companyId = null;
            $this->emit("restore");

        }       
    }

 
    public function saveCompany()
    {
        $this->validate();
 
        $company = Company::create([
            'name' => $this->name,
        ]);

        $company->users()->sync(auth()->user()->id);

        $this->name = "";
        $this->createNewCompany = false; 
        $this->active = true;
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewCompany()
    {
        if ($this->createNewCompany == false) {
            $this->name = "";
        }
    }

    public function editCompany($id)
    {
        
        $company = Company::find($id);   
        
        $this->company = $company;
        
        $this->name = $company->name;

        $this->active = true;

        $this->editCompany = true; 
    }

    public function updateCompany()
    {
        $this->validate();             

        Company::find($this->company->id)->update([

            'name' => $this->name,
        ]);        
        $this->name = null;     
        $this->company = null;
        $this->editCompany = false; 
        $this->active = true;
        $this->emit('updated');
    }

    public function downloadCompanies()
    {        
        return (new CompaniesExport(['search' => $this->search],['active' => $this->active]))->download('companies.xlsx'); 
    }

    public function showCompany($id)
    {
        $this->companyShow = Company::where('id',$id)->with('users')->first();
        $this->showCompany = true;
        $this->active = true;
    }

    public function closeShowCompany()
    {
        $this->showCompany = false;

        $this->companyShow = null;        
    }

    public function active($active)
    {
        
        $this->active = $active;
    }

}
