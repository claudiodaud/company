<?php

namespace App\Http\Livewire\Users;



use App\Models\Company;
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
use App\Exports\CompanyUsersExport;


class UserIndexCompany extends Component
{
    use WithPagination;



    public $deleteUser = false;
    public $forceDeleteUser = false;
    public $restoreUser = false;
    public $userId; 
    public $passwordUser;
    public $companyId = null; 


    public $createNewUser = false; 


    public $userEdit;
    public $userShow;

    
    public $editUser = false;
    public $showUser = false;

    public $search; 


    public $name; 
    public $email; 
    public $password;


    public $active = true;
        

    public function mount($id)
    {
        $this->companyId = $id ; 
    }

    public function render()
    {
        $usersByCompany = Company::find($this->companyId)->users();
        
        
        if ($this->active == true) {

            $users = $usersByCompany->Where(function($query) {
                             $query  ->orWhere('users.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('users.id', 'DESC')->paginate(10);
        }else{

             $users = $usersByCompany->Where(function($query) {
                             $query  ->orWhere('users.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.updated_at', 'like', '%'.$this->search.'%');                            
                                })->orderBy('users.id', 'DESC')->onlyTrashed()->paginate(10);
                                   
        }
 
        
        return view('livewire.users.user-index-company', [

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

    public function confirmForceUserDeletion($userId)
    {
        $this->userId = $userId; 
        $this->forceDeleteUser = true;
    }

    public function confirmRestoreUser($userId)
    {
        $this->userId = $userId; 
        $this->restoreUser = true;
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
            $this->userId = null;
            $this->emit("deleted");

        }       
    }

    public function forceDeleteUser()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $user = User::withTrashed()->find($this->userId);
            $user->forceDelete();
            $this->forceDeleteUser = false;
            $this->password = null;
            $this->userId = null;
            $this->emit("forceDeleted");

        }       
    }

    public function restoreUser()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $user = User::withTrashed()->find($this->userId);
            $user->restore();
            $this->restoreUser = false;
            $this->password = null;
            $this->userId = null;
            $this->emit("restore");

        }       
    }

 
    public function saveUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', 
            'password' => 'required|max:50'    
        ]);
    

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),                         
              
        ]);

        $user->companies()->sync($this->companyId);

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
        $this->email = $user->email;
        $this->password = $user->password;

        $this->editUser = true; 
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                        'required','string','email','max:255',
                        Rule::unique('users')->ignore($this->user->id),
                    ],               
        ]);               

        $user = User::find($this->user->id)->update([

            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),

        ]);        

        $this->name = null;     
        $this->user = null;
        $this->editUser = false; 
        $this->emit('updated');
    }



    public function downloadUsers()
    {
       
        return (new CompanyUsersExport(['search' => $this->search], ['companyId' => $this->companyId], ['active' => $this->active]))->download('users.xlsx'); 
       
    }

    public function showUser($id)
    {
        $this->userShow = User::where('id',$id)->first();
        

        $this->showUser = true;
    }

    public function closeShowUser()
    {
        $this->showUser = false;

        $this->userShow = null;        
    }

    public function active($active)
    {
        
        $this->active = $active;
    }



}
