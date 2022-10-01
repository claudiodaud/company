<?php

namespace App\Http\Livewire\Quotes;



use App\Exports\ContractQuotesExport;
use App\Models\Contract;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Quote;
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


class QuoteIndexContract extends Component
{
    use WithPagination;



    public $deleteQuote = false;
    public $forceDeleteQuote = false;
    public $restoreQuote = false;
    public $quoteId; 
    public $customerId;
    public $passwordQuote;
    public $contractId = null; 


    public $createNewQuote = false; 


    public $quoteEdit;
    public $quoteShow;

    
    public $editQuote = false;
    public $showQuote = false;

    public $search; 


    public $name; 
    
    public $active = true;

   
    public $permissions;
        

    public function mount($id)
    {
        $this->contractId = $id ; 

        $this->customerId = Contract::find($id)->customer_id;

        $this->getPermissions();
    }

    public function render()
    {
        $quotesByContract = Contract::find($this->contractId)->quotes();
        
        
        if ($this->active == true) {

            $quotes = $quotesByContract->Where(function($query) {
                             $query  ->orWhere('quotes.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('quotes.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('quotes.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('quotes.id', 'DESC')->paginate(10);
        }else{

             $quotes = $quotesByContract->Where(function($query) {
                             $query  ->orWhere('quotes.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('quotes.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('quotes.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('quotes.id', 'DESC')->onlyTrashed()->paginate(10);
                                   
        }
 
        if(in_array("viewQuotes", $this->permissions)){
            
            return view('livewire.quotes.quote-index-contract', [

                'quotes' => $quotes,

            ]);

        }else{

            throw UnauthorizedException::forPermissions($this->permissions);

        }
    }

    public function getPermissions()
    {
        $quoteWithRolesAndPermissions = User::where('id',auth()->user()->id)->with('roles')->first();
        $quoteWithDirectsPermissions = User::where('id',auth()->user()->id)->with('permissions')->first();
        
        
        $permissions = [];

        //find permissions for roles
        foreach ($quoteWithRolesAndPermissions->roles as $key => $role) {
           
            $role = Role::where('id',$role->id)->with('permissions')->first();
                
                foreach ($role->permissions as $key => $permission) {
                    array_push($permissions,$permission->name);
                }                
        }

        //find directs permissions
        foreach ($quoteWithDirectsPermissions->permissions as $key => $permission) {
        
            array_push($permissions,$permission->name);
                         
        }

        $this->permissions = array_unique($permissions);

        //dd($this->permissions);
    }

    
    public function updatingSearch()
    {
        $this->resetPage();        
    }

    public function confirmQuoteDeletion($quoteId)
    {
        $this->quoteId = $quoteId; 
        $this->deleteQuote = true;
    }

    public function confirmForceQuoteDeletion($quoteId)
    {
        $this->quoteId = $quoteId; 
        $this->forceDeleteQuote = true;
    }

    public function confirmRestoreQuote($quoteId)
    {
        $this->quoteId = $quoteId; 
        $this->restoreQuote = true;
    }

    public function deleteQuote()
    {

        if (! Hash::check($this->passwordQuote, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            Quote::destroy($this->quoteId);


            $this->deleteQuote = false;
            $this->passwordQuote = null;
            $this->quoteId = null;
            $this->emit("deleted");

        }       
    }

    public function forceDeleteQuote()
    {

        if (! Hash::check($this->passwordQuote, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $quote = Quote::withTrashed()->find($this->quoteId);
            $quote->forceDelete();
            $this->forceDeleteQuote = false;
            $this->passwordQuote = null;
            $this->quoteId = null;
            $this->emit("forceDeleted");

        }       
    }

    public function restoreQuote()
    {

        if (! Hash::check($this->passwordQuote, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $quote = Quote::withTrashed()->find($this->quoteId);
            $quote->restore();
            $this->restoreQuote = false;
            $this->passwordQuote = null;
            $this->quoteId = null;
            $this->emit("restore");

        }       
    }

 
    public function saveQuote()
    {
        $this->validate([
            'name' => 'required|string|max:255',             
        ]);
    

        $quote = Quote::create([
            'name' => $this->name, 
            'contract_id' => $this->contractId,
            'customer_id' => 2,            
        ]);

       // $quote->companies()->sync($this->contractId);

        $this->name = "";
        
        $this->createNewQuote = false; 
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewQuote()
    {
        if ($this->createNewQuote == false) {
            $this->name = "";
        }
    }

    public function editQuote($id)
    {
        
        $quote = Quote::find($id);   
        
        $this->quote = $quote;
        $this->name = $quote->name;        
        $this->editQuote = true; 
    }

    public function updateQuote()
    {
        $this->validate([
            'name' => 'required|string|max:255',
                         
        ]);               

        $quote = Quote::find($this->quote->id)->update([

            'name' => $this->name,
            'contract_id' => $this->contractId,
            'customer_id' => 2,            
        ]);        

        $this->name = null;     
        $this->quote = null;
        $this->editQuote = false; 
        $this->emit('updated');
    }



    public function downloadQuotes()
    {
       
        return (new ContractQuotesExport(['search' => $this->search], ['contractId' => $this->contractId], ['active' => $this->active]))->download('quotes.xlsx'); 
       
    }

    public function showQuote($id)
    {
        $this->quoteShow = Quote::where('id',$id)->first();
        

        $this->showQuote = true;
    }

    public function closeShowQuote()
    {
        $this->showQuote = false;

        $this->quoteShow = null;        
    }


    public function active($active)
    {
        
        $this->active = $active;
    }



}