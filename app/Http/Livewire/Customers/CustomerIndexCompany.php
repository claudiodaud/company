<?php

namespace App\Http\Livewire\Customers;

use App\Exports\CompanyCustomersExport;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\withMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Spatie\Permission\Exceptions\UnauthorizedException;
use DB;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class CustomerIndexCompany  extends Component
{
    use WithPagination;
    use WithFileUploads;



    public $deleteCustomer = false;
    public $forceDeleteCustomer = false;
    public $restoreCustomer = false;
    
    //to delete dont touch...
    public $customerId; 
    public $passwordUser;
    public $password;
    public $companyId = null; 


    public $createNewCustomer = false; 



    public $customerEdit;
    public $customerShow;

    
    public $editCustomer = false;
    public $showCustomer = false;

    public $search; 


    //Fields
    public $social_name;
    public $fantasy_name;
   
    public $dni;
    public $logo_photo_path;
    public $logo_saved; // saved actually logo_photo_path update method
    
    public $detail;
    

    public $active = true;

    //Add and Remove Users
    public $addRemoveUsers;
    public $usersAddByCompany;
    public $usersAddByCustomer; 

    public $permissions;   
        

    public function mount($id)
    {
        $this->companyId = $id ; 
        $this->getPermissions();
    }

    public function render()
    {
        

        $usersByCompany = Company::find($this->companyId)->users()->where(function($query) {
            $query->where('users.id',auth()->user()->id);
        });

        if(count($usersByCompany->get()) > 0){


            

            $customersByCompany = Company::find($this->companyId)->customers();
            
           
            
            
            if ($this->active == true) {

                $customers = $customersByCompany->Where(function($query) {
                                 $query  ->orWhere('companies.social_name', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.fantasy_name', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('companies.id', 'DESC')->paginate(10);
            }else{

                 $customers = $customersByCompany->Where(function($query) {
                                 $query  ->orWhere('companies.social_name', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.fantasy_name', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('companies.id', 'DESC')->onlyTrashed()->paginate(10);
                                       
            }
     
            
            if(in_array("viewCustomers", $this->permissions)){
                
                return view('livewire.customers.customer-index-company', [

                    'customers' => $customers,

                ]);

            }else{

                throw UnauthorizedException::forPermissions($this->permissions);

            }
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

    public function confirmCustomerDeletion($customerId)
    {
        $this->customerId = $customerId; 
        $this->deleteCustomer = true;
    }

    public function confirmForceCustomerDeletion($customerId)
    {
        $this->customerId = $customerId; 
        $this->forceDeleteCustomer = true;
    }

    public function confirmRestoreCustomer($customerId)
    {
        $this->customerId = $customerId; 
        $this->restoreCustomer = true;
    }

    public function deleteCustomer()
    {

        if (! Hash::check($this->passwordUser, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            Company::destroy($this->customerId);


            $this->deleteCustomer = false;
            $this->passwordUser = null;
            $this->customerId = null;
            $this->emit("deleted");

        }       
    }

    public function forceDeleteCustomer()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $customer = Company::withTrashed()->find($this->customerId);

            $customer->forceDelete();
            $this->forceDeleteCustomer = false;
            $this->password = null;
            $this->customerId = null;
            $this->emit("forceDeleted");

        }       
    }

    public function restoreCustomer()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $customer = Company::withTrashed()->find($this->customerId);
            $customer->restore();
            $this->restoreCustomer = false;
            $this->password = null;
            $this->customerId = null;
            $this->emit("restore");

        }       
    }

 
    public function saveCustomer()
    {
      
        $this->validate([
            'social_name' => 'required|string|max:70|min:1',  
            'fantasy_name' => 'required|string|max:70',
            'dni' => 'required|max:12|min:12', 
            'detail' => 'max:500',
        ]);
 
        $customer = Company::create([

            'type' => 1,
            'social_name' => $this->social_name,
            'fantasy_name' => $this->fantasy_name,
            'dni' => $this->dni,
            'detail' => $this->detail,

        ]);
        

        $customer->users()->attach(auth()->user()->id);

        $company = Company::find($this->companyId);
        $company->customers()->attach($customer);

        if ($this->logo_photo_path ) {

            $this->logo_photo_path->store('companies','public');
            
            Company::find($customer->id)->update([
            'logo_photo_path' => $this->logo_photo_path->hashName(),
            ]);
        }
        
        
        $this->createNewCustomer = false; 
        $this->active = true;
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewCustomer()
    {
        if ($this->createNewCustomer == false) {
            $this->clearFields();
        }
    }

    public function clearFields()
    {
        
        //Clear fields
        $this->social_name          = "";
        $this->fantasy_name         = "";
        $this->dni                  = "";
        $this->detail               = "";
        $this->logo_photo_path      = "";
        $this->logo_saved           = "";
       
    }

    public function editCustomer($id)
    {
        $this->clearFields();
        
        $customer = Company::find($id);   
        
        $this->customer = $customer;
        $this->social_name          = $customer->social_name;
        $this->fantasy_name         = $customer->fantasy_name;       
        $this->dni                  = $customer->dni;
        $this->logo_saved           = $customer->logo_photo_path;       
        $this->detail               = $customer->detail;

        $this->active = true;

        $this->editCustomer = true; 
    }

    public function updatededitCustomer()
    {
        if($this->editCustomer == false){
            $this->clearFields();
        }
    }

    public function updateCustomer()
    {
        
        $this->validate([
            'social_name' => 'required|string|max:70|min:1',  
            'fantasy_name' => 'string|max:70',            
            'dni' => 'required|max:12|min:12', 
            'detail' => 'max:500',
        ]);            

        Company::find($this->customer->id)->update([

            'social_name' => $this->social_name,
            'fantasy_name' => $this->fantasy_name,            
            'dni' => $this->dni,           
            'detail' => $this->detail,
        ]);  

        if ($this->logo_photo_path != null) {

            if ($this->logo_saved != null) {
                Storage::delete('public/companies'.$this->logo_saved);
            }            
            

            $this->logo_photo_path->store('companies','public');

            
            Company::find($this->customer->id)->update([

            
                'logo_photo_path' => $this->logo_photo_path->hashName(),
            
            ]); 
            
        }

        $this->customer = null;
        $this->editCustomer = false; 
        $this->active = true;
        $this->emit('updated');
    }


// TODO 
    public function downloadCustomers()
    {
       
        return (new CompanyCustomersExport(['search' => $this->search], ['companyId' => $this->companyId], ['active' => $this->active]))->download('customers.xlsx'); 
       
    }

    public function showCustomer($id)
    {
        $this->customerShow = Company::where('id',$id)->first();
        

        $this->showCustomer = true;
    }

    public function closeShowCustomer()
    {
        $this->showCustomer = false;

        $this->customerShow = null;        
    }

    

    public function active($active)
    {
        
        $this->active = $active;
    }

}