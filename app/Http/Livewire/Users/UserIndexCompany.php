<?php

namespace App\Http\Livewire\Users;



use App\Exports\CompanyUsersExport;
use App\Models\Company;
use App\Models\Permission;
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
    public $addExitingUser = false; 

    public $findEmail;
    public $userFound;
    public $msg;


    public $userEdit;
    public $userShow;

    
    public $editUser = false;
    public $showUser = false;

    public $search; 


    public $name; 
    public $email; 
    public $password;


    public $active = true;

    //Add and Remove Users
    public $addRemoveRoles;
    public $rolesAddByUser;
    public $roles; 

    //Add and Remove Users
    public $addRemovePermissions;
    public $permissionsAddByUser;
    public $permissionsForAddByUser;
     


    public $permissions;
        

    public function mount($id)
    {
        $this->companyId = $id ; 

        $this->getPermissions();
    }

    public function render()
    {
        //evaluamos si el usuario pertenece a la compañia. 
        // $usersByCompany = Company::find($this->companyId)->users()->where(function($query) {
        //     $query->where('users.id',auth()->user()->id);
        // });

        $usersByCompany = Company::find($this->companyId)->users();

        //dd($usersByCompany);
        //si el usuario pertenece a la compañia mostramos los registros 
        // si el usuario no pertence a la compañia mostramos un error 403 sin autorización 

        if(count($usersByCompany->get()) > 0){
            
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
            //ahora evealuamos como segunda capa que el usuario tenga el permiso para ver el modulo 
            //si la respuesta es si le mostramos los registros 
            //si  la respuesta es no le mostramos un error 403 sin autorización
            
            // NOTA IMPORTANTE !!!
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // DE ESTA FORMA PROTEGEMOS LA RUTA SI ES QUE ALGUN USUARIO CREATIVO DIGITA LA RUTA DE OTRO REGISTROS APROVECHANDO LOS IDS      //
            // LUEGO EN UNA SEGUNDA CAPA VALIDAMOS LOS PERMISOS QUE HAN SIDO RELAMENTE ASIGNADOS AL USUARIO                                 //
            // CON ESTO TENEMOS MAXIMA PROTECCION EN LA PROTECCION DE DATOS DE USUARIOS DISTINTOS                                           //
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if(in_array("viewUsers", $this->permissions)){
                
                return view('livewire.users.user-index-company', [

                    'users' => $users,

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
            //$user->forceDelete(); // tiene el potencial de borrar usuarios que podrian estar en otras compañias 
            $user->companies()->detach($this->companyId);
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
            $this->email = "";
            $this->password = "";
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

    public function addRemoveRoles($user_id)
    {
           
        $this->rolesAddByUser = User::where('id',$user_id)->with('roles')->first();
       
        $rolesAddIds = [];
        foreach ($this->rolesAddByUser->roles as $key => $role) {
            array_push($rolesAddIds,$role->id);
        }
        

        $this->roles = Role::all();
        $rolesForAddIds = [];
        foreach ($this->roles as $key => $role) {
            array_push($rolesForAddIds,$role->id);
        }
        
        
        foreach($rolesAddIds as $role){
            $remove = array_search($role, $rolesForAddIds);
            unset($rolesForAddIds[$remove]);
        }

       
    
        $this->rolesForAddByUser = Role::whereIn('id', $rolesForAddIds)->get();

        
        $this->addRemoveRoles = true;
    }

    public function closeAddRemoveRoles()
    {
        $this->addRemoveRoles = false;           
    }

    public function addRoleToUser($role_id,$user_id)
    {
        
        $user = User::find($user_id);
        
        $user->roles()->attach($role_id);
        
        $this->mount($this->companyId);
        
        $this->addRemoveRoles($user_id);

    }

    public function removeRoleToUser($role_id,$user_id)
    {
        $user = User::find($user_id);
        
        $user->roles()->detach($role_id);
        
        $this->mount($this->companyId);
        
        $this->addRemoveRoles($user_id);

    }

     public function addRemovePermissions($user_id)
    {
           
        $this->permissionsAddByUser = User::find($user_id);
       
        $permissionsAddIds = [];
        foreach ($this->permissionsAddByUser->permissions as $key => $permission) {
            array_push($permissionsAddIds,$permission->id);
        }
        

        $permissions = Permission::all();
        $permissionsForAddIds = [];
        foreach ($permissions as $key => $permission) {
            array_push($permissionsForAddIds,$permission->id);
        }
        
        
        foreach($permissionsAddIds as $permission){
            $remove = array_search($permission, $permissionsForAddIds);
            unset($permissionsForAddIds[$remove]);
        }

       
    
        $this->permissionsForAddByUser = Permission::whereIn('id', $permissionsForAddIds)->get();

        
        $this->addRemovePermissions = true;
    }

    public function closeAddRemovePermission()
    {
        $this->addRemovePermissions = false;           
    }

    public function addPermissionToUser($permission_id,$user_id)
    {
        
        $user = User::find($user_id);
        
        $user->permissions()->attach($permission_id);
        
        $this->mount($this->companyId);

        $this->addRemovePermissions($user_id);

    }

    public function removePermissionToUser($permission_id,$user_id)
    {
        $user = User::find($user_id);
        
        $user->permissions()->detach($permission_id);

        $this->mount($this->companyId);
        
        $this->addRemovePermissions($user_id);

    }

    public function active($active)
    {
        
        $this->active = $active;
    }


    public function updatedFindEmail()
    {
        $this->userFound = User::where('email',$this->findEmail)->first();

        if ($this->userFound) {

            $this->msg = "User Found !";

        }else{

            $this->msg = "User not Found";
        }
    }


    public function addExitingUser()
    {
        $user = User::where('email',$this->findEmail)->first();

        

        if ($user) {

            $user->companies()->sync($this->companyId);

            $this->findEmail = "";
            $this->addExitingUser = false;
            $this->userNotFound = "";

            $this->emit('updated');
        }
        
    }



}
