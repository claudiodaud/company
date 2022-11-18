<?php

namespace App\Http\Livewire\Customers;

use App\Exports\CompanyCustomersExport;
use App\Models\Company;
use App\Models\Customer;
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


class CustomerIndexCompany  extends Component
{
    use WithPagination;



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


    public $name; 
    

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
                                 $query  ->orWhere('customers.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('customers.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('customers.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('customers.id', 'DESC')->paginate(10);
            }else{

                 $customers = $customersByCompany->Where(function($query) {
                                 $query  ->orWhere('customers.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('customers.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('customers.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('customers.id', 'DESC')->onlyTrashed()->paginate(10);
                                       
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

            Customer::destroy($this->customerId);


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

            $customer = Customer::withTrashed()->find($this->customerId);
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

            $customer = Customer::withTrashed()->find($this->customerId);
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
            'name' => 'required|string|max:255',
              
        ]);
    

        $customer = Customer::create([
            'name' => $this->name,                                    
            'company_id' => $this->companyId,  
        ]);

       

        $this->name = "";
        
        $this->createNewCustomer = false; 
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewCustomer()
    {
        if ($this->createNewCustomer == false) {
            $this->name = "";
        }
    }

    public function editCustomer($id)
    {
        
        $customer = Customer::find($id);   
        
        $this->customer = $customer;
        $this->name = $customer->name;
        

        $this->editCustomer = true; 
    }

    public function updateCustomer()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            
        ]);               

        $customer = Customer::find($this->customer->id)->update([

            'name' => $this->name,
            'company_id' => $this->companyId,
            
        ]);        

        $this->name = null;     
        $this->customer = null;
        $this->editCustomer = false; 
        $this->emit('updated');
    }


// TODO 
    public function downloadCustomers()
    {
       
        return (new CompanyCustomersExport(['search' => $this->search], ['companyId' => $this->companyId], ['active' => $this->active]))->download('customers.xlsx'); 
       
    }

    public function showCustomer($id)
    {
        $this->customerShow = Customer::where('id',$id)->first();
        

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