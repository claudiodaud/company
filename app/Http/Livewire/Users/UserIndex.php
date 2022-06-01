<?php

namespace App\Http\Livewire\Users;


use App\Exports\CompaniesExport;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\withMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;


class UserIndex extends Component
{
    use WithPagination;



    public $deleteUser = false;
    public $UserId; 
    public $passwordUser;



    public $createNewUser = false; 


    public $userEdit;
    public $userShow;

    
    public $editUser = false;
    public $showUser = false;

    public $search; 


    public $name; 
    public $email; 
    public $password;


    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users', 
        'password' => 'required|max:50'       
    ];

    public function render()
    {

        // TODO emitir un evento desde el modulo de companies para ver la lista de users 

        $usersByCompany = Company::find(1)->users();
        $users = $usersByCompany->Where(function($query) {
                             $query  ->orWhere('users.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.updated_at', 'like', '%'.$this->search.'%');                            
                             })->orderBy('users.id', 'DESC')->paginate(10);
 
        
        return view('livewire.users.user-index', [

            'users' => $users,

        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();        
    }

    public function confirmUserDeletion($userId)
    {
        $this->userId = $userId; 
        $this->deleteUser = true;
    }

    public function deleteUser()
    {

        if (! Hash::check($this->passwordUser, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            User::destroy($this->userId);
            $this->deleteUser = false;
            $this->passwordUser = null;
            $this->UserId = null;
            $this->emit("deleted");

        }       
    }

 
    public function saveUser()
    {
        $this->validate();
    

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'company_id' => auth::user()->company_id,
        ]);

        $this->name = "";
        $this->email = "";
        $this->password = "";
        $this->createNewUser = false; 
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewUser()
    {
        if ($this->createNewUser == false) {
            $this->name = "";
        }
    }

    public function editUser($id)
    {
        
        $user = User::find($id);   
        
        $this->user = $user;
        
        $this->name = $user->name;

        $this->editUser = true; 
    }

    public function updateUser()
    {
        $this->validate();             

        User::find($this->user->id)->update([

            'name' => $this->name,
        ]);        
        $this->name = null;     
        $this->user = null;
        $this->editUser = false; 
        $this->emit('updated');
    }

    public function downloadUser()
    {
       
       // return (new CompaniesExport($this->search))->download('companies.xlsx'); 
       
    }

    public function showUser($id)
    {
        $this->userShow = User::where('id',$id)->with('company')->first();
        

        $this->showUser = true;
    }

    public function closeShowUser()
    {
        $this->showUser = false;

        $this->userShow = null;        
    }



}
