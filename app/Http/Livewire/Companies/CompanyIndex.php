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
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class CompanyIndex extends Component
{
    use WithPagination;
    use WithFileUploads;



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

    //Fields
    public $social_name;
    public $fantasy_name;
   
    public $dni;
    public $logo_photo_path;
    public $logo_saved; // saved actually logo_photo_path update method
    
    public $detail;

    public $active = true;

    public $permissions;


    public function mount()
    {
        
        $this->getPermissions();
       
    }

    public function updatedLogo()
    {
        

        $this->validate([
        
            'logo_photo_path' => 'image|mimes:jpg,jpeg,png,svg,gif|size:30720',
            // 'image' => 'required|image|size:1024||dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000'
            //'image' => 'required|image|size:1024|dimensions:ratio=3/2'
        ]);

         
    }


    public function render()
    {
        $companiesByUser = User::find(auth()->user()->id)->companies();

        if ($this->active == true) {

            $companies = $companiesByUser->Where(function($query) {
                             $query  ->orWhere('companies.social_name', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.fantasy_name', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('companies.id', 'DESC')->where('companies.type',0)->paginate(10);
        }else{

             $companies = $companiesByUser->Where(function($query) {
                             $query  ->orWhere('companies.social_name', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.fantasy_name', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('companies.id', 'DESC')->where('companies.type',0)->onlyTrashed()->paginate(10);
                                   
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
        $this->validate([
            'social_name' => 'required|string|max:70|min:1',  
            'fantasy_name' => 'required|string|max:70',
            // 'email' => 'string|email|unique:companies,email',
            // 'phone' => 'string|max:70|unique:companies,phone',
            // 'web' => 'string|max:70',
            // 'adress' => 'string|max:70',
            'dni' => 'required|max:12|min:12', 
            // 'headline_name' => 'string|max:70',
            // 'bank_name' => 'string|max:70',
            // 'type_account' => 'string|max:70',
            // 'account_number' => 'string|max:100|unique:companies,account_number',
            // 'notification_email' => 'string|email',
            'detail' => 'max:500',
        ]);
 
        $company = Company::create([
            'type' => 0,
            'social_name' => $this->social_name,
            'fantasy_name' => $this->fantasy_name,
            // 'email' => $this->email,
            // 'phone' => $this->phone,
            // 'web' => $this->web,
            // 'adress' => $this->adress,
            'dni' => $this->dni,
            
            // 'headline_name' => $this->headline_name,
            // 'bank_name' => $this->bank_name,
            // 'type_account' => $this->type_account,
            // 'account_number' => $this->account_number,
            // 'notification_email' => $this->notification_email,
            'detail' => $this->detail,

        ]);

        $company->users()->sync(auth()->user()->id);

        if ($this->logo_photo_path ) {

            $this->logo_photo_path->store('companies','public');
            
            Company::find($company)->update([
            'logo_photo_path' => $this->logo_photo_path->hashName(),
            ]);
        }
        
        
        $this->createNewCompany = false; 
        $this->active = true;
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedcreateNewCompany()
    {
        if($this->createNewCompany == true){
            $this->clearFields();
        }
    }

    public function clearFields()
    {
        
        //Clear fields
        $this->social_name          = "";
        $this->fantasy_name         = "";
        // $this->email                = "";
        // $this->phone                = "";
        // $this->web                  = "";
        // $this->adress               = "";
        $this->dni                  = "";
        // $this->headline_name        = "";
        // $this->bank_name            = "";
        // $this->type_account         = "";
        // $this->account_number       = "";
        // $this->notification_email   = "";
        $this->detail               = "";

        $this->logo_photo_path = "";
        $this->logo_saved = "";
       
    }

    public function editCompany($id)
    {
        $this->clearFields();

        $company = Company::find($id);   
        
        $this->company = $company;
        
        $this->social_name          = $company->social_name;
        $this->fantasy_name         = $company->fantasy_name;
        // $this->email                = $company->email;
        // $this->phone                = $company->phone;
        // $this->web                  = $company->web;
        // $this->adress               = $company->adress;
        $this->dni                  = $company->dni;
        $this->logo_saved           = $company->logo_photo_path;
        // $this->headline_name        = $company->headline_name;
        // $this->bank_name            = $company->bank_name;
        // $this->type_account         = $company->type_account;
        // $this->account_number       = $company->account_number;
        // $this->notification_email   = $company->notification_email;
        $this->detail               = $company->detail;


        $this->active = true;

        $this->editCompany = true; 
    }

    public function updatededitCompany()
    {
        if($this->editCompany == false){
            $this->clearFields();
        }
    }

    public function updateCompany()
    {
        $this->validate([
            'social_name' => 'required|string|max:70|min:1',  
            'fantasy_name' => 'string|max:70',
            // 'email' => 'string|email|unique:companies,email,'.$this->company->id.',id',
            // 'phone' => 'string|max:70|unique:companies,phone,'.$this->company->id.',id',
            // 'web' => 'string|max:70',
            // 'adress' => 'string|max:70',
            'dni' => 'required|max:12|min:12', // unique:companies,dni,'.$this->company->id.',id', 
            // 'headline_name' => 'string|max:70',
            // 'bank_name' => 'string|max:70',
            // 'type_account' => 'string|max:70',
            // 'account_number' => 'string|max:100|unique:companies,account_number,'.$this->company->id.',id',
            // 'notification_email' => 'string|email',
            'detail' => 'max:500',
        ]);            

        Company::find($this->company->id)->update([

            'social_name' => $this->social_name,
            'fantasy_name' => $this->fantasy_name,
            // 'email' => $this->email,
            // 'phone' => $this->phone,
            // 'web' => $this->web,
            // 'adress' => $this->adress,
            'dni' => $this->dni,
            //'logo_photo_path' => $this->logo_photo_path->hashName(),
            // 'headline_name' => $this->headline_name,
            // 'bank_name' => $this->bank_name,
            // 'type_account' => $this->type_account,
            // 'account_number' => $this->account_number,
            // 'notification_email' => $this->notification_email,
            'detail' => $this->detail,
        ]);  

        if ($this->logo_photo_path != null) {

            if ($this->logo_saved != null) {
                Storage::delete('public/companies'.$this->logo_saved);
            }            
            

            $this->logo_photo_path->store('companies','public');

            
            Company::find($this->company->id)->update([

            
                'logo_photo_path' => $this->logo_photo_path->hashName(),
            
            ]); 
            
        }      
            
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
