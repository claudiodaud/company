<?php

namespace App\Http\Livewire\Conditions;


use App\Exports\CompanyConditionsExport;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Condition;
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


class ConditionIndexCompany extends Component
{
    use WithPagination;



    public $deleteCondition = false;
    public $forceDeleteCondition = false;
    public $restoreCondition = false;
    public $conditionId; 
    public $passwordCondition;
    public $companyId = null; 


    public $createNewCondition = false; 


    public $conditionEdit;
    public $conditionShow;

    
    public $editCondition = false;
    public $showCondition = false;

    public $search; 


    public $name; 
    
    public $active = true;

   
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

            $conditionsByCompany = Company::find($this->companyId)->conditions();
            
            
            if ($this->active == true) {

                $conditions = $conditionsByCompany->Where(function($query) {
                                 $query  ->orWhere('conditions.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('conditions.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('conditions.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('conditions.id', 'DESC')->paginate(10);
            }else{

                 $conditions = $conditionsByCompany->Where(function($query) {
                                 $query  ->orWhere('conditions.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('conditions.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('conditions.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('conditions.id', 'DESC')->onlyTrashed()->paginate(10);
                                       
            }
     
            if(in_array("viewConditions", $this->permissions)){
                
                return view('livewire.conditions.condition-index-company', [

                    'conditions' => $conditions,

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
        $conditionWithRolesAndPermissions = User::where('id',auth()->user()->id)->with('roles')->first();
        $conditionWithDirectsPermissions = User::where('id',auth()->user()->id)->with('permissions')->first();
        
        
        $permissions = [];

        //find permissions for roles
        foreach ($conditionWithRolesAndPermissions->roles as $key => $role) {
           
            $role = Role::where('id',$role->id)->with('permissions')->first();
                
                foreach ($role->permissions as $key => $permission) {
                    array_push($permissions,$permission->name);
                }                
        }

        //find directs permissions
        foreach ($conditionWithDirectsPermissions->permissions as $key => $permission) {
        
            array_push($permissions,$permission->name);
                         
        }

        $this->permissions = array_unique($permissions);

        //dd($this->permissions);
    }

    
    public function updatingSearch()
    {
        $this->resetPage();        
    }

    public function confirmConditionDeletion($conditionId)
    {
        $this->conditionId = $conditionId; 
        $this->deleteCondition = true;
    }

    public function confirmForceConditionDeletion($conditionId)
    {
        $this->conditionId = $conditionId; 
        $this->forceDeleteCondition = true;
    }

    public function confirmRestoreCondition($conditionId)
    {
        $this->conditionId = $conditionId; 
        $this->restoreCondition = true;
    }

    public function deleteCondition()
    {

        if (! Hash::check($this->passwordCondition, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            Condition::destroy($this->conditionId);


            $this->deleteCondition = false;
            $this->passwordCondition = null;
            $this->conditionId = null;
            $this->emit("deleted");

        }       
    }

    public function forceDeleteCondition()
    {

        if (! Hash::check($this->passwordCondition, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $condition = Condition::withTrashed()->find($this->conditionId);
            $condition->forceDelete();
            $this->forceDeleteCondition = false;
            $this->passwordCondition = null;
            $this->conditionId = null;
            $this->emit("forceDeleted");

        }       
    }

    public function restoreCondition()
    {

        if (! Hash::check($this->passwordCondition, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $condition = Condition::withTrashed()->find($this->conditionId);
            $condition->restore();
            $this->restoreCondition = false;
            $this->passwordCondition = null;
            $this->conditionId = null;
            $this->emit("restore");

        }       
    }

 
    public function saveCondition()
    {
        $this->validate([
            'name' => 'required|string|max:255',             
        ]);
    

        $condition = Condition::create([
            'name' => $this->name,  
            'company_id' => $this->companyId,  
        ]);

        $this->name = "";
        
        $this->createNewCondition = false; 
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewCondition()
    {
        if ($this->createNewCondition == false) {
            $this->name = "";
        }
    }

    public function editCondition($id)
    {
        
        $condition = Condition::find($id);   
        
        $this->condition = $condition;
        $this->name = $condition->name;        
        $this->editCondition = true; 
    }

    public function updateCondition()
    {
        $this->validate([
            'name' => 'required|string|max:255',
                         
        ]);               

        $condition = Condition::find($this->condition->id)->update([

            'name' => $this->name,
        ]);        

        $this->name = null;     
        $this->condition = null;
        $this->editCondition = false; 
        $this->emit('updated');
    }



    public function downloadConditions()
    {
       
        return (new CompanyConditionsExport(['search' => $this->search], ['companyId' => $this->companyId], ['active' => $this->active]))->download('conditions.xlsx'); 
       
    }

    public function showCondition($id)
    {
        $this->conditionShow = Condition::where('id',$id)->first();
        

        $this->showCondition = true;
    }

    public function closeShowCondition()
    {
        $this->showCondition = false;

        $this->conditionShow = null;        
    }


    public function active($active)
    {
        
        $this->active = $active;
    }



}
