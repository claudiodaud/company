<?php

namespace App\Http\Livewire\Contracts;

use App\Exports\CustomerContractsExport;
use App\Models\Contract;
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


class ContractIndexCustomer extends Component
{
    use WithPagination;



    public $deleteContract = false;
    public $forceDeleteContract = false;
    public $restoreContract = false;
    
    //to delete dont touch...
    public $contractId; 
    public $customerId;
    public $companyId;
    public $passwordUser;
    public $password;
    


    public $createNewContract = false; 


    public $contractEdit;
    public $contractShow;

    
    public $editContract = false;
    public $showContract = false;

    public $search; 


    public $name;
    public $detail; 
    

    public $active = true;

    //Add and Remove Users
    public $addRemoveUsers;
    public $usersAddByCompany;
    public $usersAddByContract; 

    public $permissions;   
        

    public function mount($customer_id,$company_id)
    {
        $this->customerId = $customer_id ; 
        $this->companyId = $company_id;
        $this->getPermissions();
    }

    public function render()
    {
        $usersByCompany = Company::find($this->customerId)->users()->where(function($query) {
            $query->where('users.id',auth()->user()->id);
        });

        if(count($usersByCompany->get()) > 0){

            $contractsByCustomer = Company::find($this->customerId)->contracts();
            
            
            
            if ($this->active == true) {

                $contracts = $contractsByCustomer->Where(function($query) {
                                 $query  ->orWhere('contracts.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('contracts.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('contracts.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('contracts.id', 'DESC')->paginate(10);
            }else{

                 $contracts = $contractsByCustomer->Where(function($query) {
                                 $query  ->orWhere('contracts.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('contracts.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('contracts.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('contracts.id', 'DESC')->onlyTrashed()->paginate(10);
                                       
            }
     
            
            if(in_array("viewContracts", $this->permissions)){
                
                return view('livewire.contracts.contract-index-customer', [

                    'contracts' => $contracts,

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

    public function confirmContractDeletion($contractId)
    {
        $this->contractId = $contractId; 
        $this->deleteContract = true;
    }

    public function confirmForceContractDeletion($contractId)
    {
        $this->contractId = $contractId; 
        $this->forceDeleteContract = true;
    }

    public function confirmRestoreContract($contractId)
    {
        $this->contractId = $contractId; 
        $this->restoreContract = true;
    }

    public function deleteContract()
    {

        if (! Hash::check($this->passwordUser, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            Contract::destroy($this->contractId);


            $this->deleteContract = false;
            $this->passwordUser = null;
            $this->contractId = null;
            $this->emit("deleted");

        }       
    }

    public function forceDeleteContract()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $contract = Contract::withTrashed()->find($this->contractId);
            $contract->forceDelete();
            $this->forceDeleteContract = false;
            $this->password = null;
            $this->contractId = null;
            $this->emit("forceDeleted");

        }       
    }

    public function restoreContract()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $contract = Contract::withTrashed()->find($this->contractId);
            $contract->restore();
            $this->restoreContract = false;
            $this->password = null;
            $this->contractId = null;
            $this->emit("restore");

        }       
    }

 
    public function saveContract()
    {
        $this->validate([
            'name' => 'required|string|max:255',
              
        ]);
    

        $contract = Contract::create([
            'name' => $this->name,  
            'detail' => $this->detail,                                  
            'company_id' => $this->customerId,  
        ]);


        $this->name = "";
        
        $this->createNewContract = false; 
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewContract()
    {
        if ($this->createNewContract == false) {
            $this->name = "";
        }
    }

    public function editContract($id)
    {
        
        $contract = Contract::find($id);   
        
        $this->contract = $contract;
        $this->name = $contract->name;
        

        $this->editContract = true; 
    }

    public function updateContract()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            
        ]);               

        $contract = Contract::find($this->contract->id)->update([

            'name' => $this->name,
            'customer_id' => $this->customerId,
            
        ]);        

        $this->name = null;     
        $this->contract = null;
        $this->editContract = false; 
        $this->emit('updated');
    }


// TODO 
    public function downloadContracts()
    {
       
        return (new CustomerContractsExport(['search' => $this->search], ['customerId' => $this->customerId], ['active' => $this->active]))->download('contracts.xlsx'); 
       
    }

    public function showContract($id)
    {
        $this->contractShow = Contract::where('id',$id)->first();
        

        $this->showContract = true;
    }

    public function closeShowContract()
    {
        $this->showContract = false;

        $this->contractShow = null;        
    }

    public function addRemoveUsers($contract_id)
    {
        
        $this->usersAddByContract = Contract::where('id',$contract_id)->with('users')->first();
        
        $usersAddIds = [];
        foreach ($this->usersAddByContract->users as $key => $user) {
            array_push($usersAddIds,$user->id);
        }


        $this->usersAddByCompany = Company::where('id',$this->companyId)->with('users')->first();
        
        $usersForAddIds = [];
        foreach ($this->usersAddByCompany->users as $key => $user) {
            array_push($usersForAddIds,$user->id);
        }

        foreach($usersAddIds as $user){
            $remove = array_search($user, $usersForAddIds);
            unset($usersForAddIds[$remove]);
        }
    
        $this->usersAddByCompany = User::whereIn('id', $usersForAddIds)->get();

        
        $this->addRemoveUsers = true;
    }

    public function closeAddRemoveUsers()
    {
        $this->addRemoveUsers = false;

        $this->usersAdd = null;        
    }

    public function addUserToContract($user_id,$contract_id)
    {
        $user = User::find($user_id);
        $user->contracts()->attach($contract_id);
        $this->addRemoveUsers($contract_id);

    }

    public function removeUserToContract($user_id,$contract_id)
    {
        $user = User::find($user_id);
        $user->contracts()->detach($contract_id);
        $this->addRemoveUsers($contract_id);

    }

    public function active($active)
    {
        
        $this->active = $active;
    }

}
