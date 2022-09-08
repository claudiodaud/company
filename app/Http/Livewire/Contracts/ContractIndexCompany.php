<?php

namespace App\Http\Livewire\Contracts;

use App\Exports\CompanyContractsExport;
use App\Models\Company;
use App\Models\Contract;
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


class ContractIndexCompany extends Component
{
    use WithPagination;



    public $deleteContract = false;
    public $forceDeleteContract = false;
    public $restoreContract = false;
    
    //to delete dont touch...
    public $contractId; 
    public $passwordUser;
    public $password;
    public $companyId = null; 


    public $createNewContract = false; 


    public $contractEdit;
    public $contractShow;

    
    public $editContract = false;
    public $showContract = false;

    public $search; 


    public $name; 
    

    public $active = true;

    //Add and Remove Users
    public $addRemoveContract;
    public $usersAddByCompany;
    public $usersAddByContract;  
        

    public function mount($id)
    {
        $this->companyId = $id ; 
    }

    public function render()
    {
        $contractsByCompany = Company::find($this->companyId)->contracts();
        
        
        
        if ($this->active == true) {

            $contracts = $contractsByCompany->Where(function($query) {
                             $query  ->orWhere('contracts.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('contracts.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('contracts.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('contracts.id', 'DESC')->paginate(10);
        }else{

             $contracts = $contractsByCompany->Where(function($query) {
                             $query  ->orWhere('contracts.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('contracts.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('contracts.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('contracts.id', 'DESC')->onlyTrashed()->paginate(10);
                                   
        }
 
        
        return view('livewire.contracts.contract-index-company', [

            'contracts' => $contracts,

        ]);
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
            'company_id' => $this->companyId,  
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
            'company_id' => $this->companyId,
            
        ]);        

        $this->name = null;     
        $this->contract = null;
        $this->editContract = false; 
        $this->emit('updated');
    }


// TODO 
    public function downloadContracts()
    {
       
        return (new CompanyContractsExport(['search' => $this->search], ['companyId' => $this->companyId], ['active' => $this->active]))->download('contracts.xlsx'); 
       
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

    public function addRemoveContract($contract_id,$company_id)
    {
        
        $this->usersAddByContract = Contract::where('id',$contract_id)->with('users')->get();
        $usersAddIds = [];
        foreach ($this->usersAddByContract[0]->users as $key => $user) {
            array_push($usersAddIds,$user->id);
        }


        $this->usersAddByCompany = Company::where('id',$company_id)->with('users')->get();
        $usersForAddIds = [];
        foreach ($this->usersAddByCompany[0]->users as $key => $user) {
            array_push($usersForAddIds,$user->id);
        }

        foreach($usersAddIds as $user){
            $remove = array_search($user, $usersForAddIds);
            unset($usersForAddIds[$remove]);
        }
    
        $this->usersAddByCompany = User::whereIn('id', $usersForAddIds)->get();

        
        $this->addRemoveContract = true;
    }

    public function closeAddRemoveContract()
    {
        $this->addRemoveContract = false;

        $this->usersAdd = null;        
    }

    public function addUserToContract($user_id,$contract_id,$company_id)
    {
        $user = User::find($user_id);
        $user->contracts()->attach($contract_id);
        $this->addRemoveContract($contract_id,$company_id);

    }

    public function removeUserToContract($user_id,$contract_id,$company_id)
    {
        $user = User::find($user_id);
        $user->contracts()->detach($contract_id);
        $this->addRemoveContract($contract_id,$company_id);

    }

    public function active($active)
    {
        
        $this->active = $active;
    }

}
