<?php

namespace App\Http\Livewire\Services;



use App\Exports\CompanyServicesExport;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Service;
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


class ServiceIndexCompany extends Component
{
    use WithPagination;



    public $deleteService = false;
    public $forceDeleteService = false;
    public $restoreService = false;
    public $serviceId; 
    public $passwordService;
    public $companyId = null; 


    public $createNewService = false; 


    public $serviceEdit;
    public $serviceShow;

    
    public $editService = false;
    public $showService = false;

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
            $servicesByCompany = Company::find($this->companyId)->services();
            
            
            if ($this->active == true) {

                $services = $servicesByCompany->Where(function($query) {
                                 $query  ->orWhere('services.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('services.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('services.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('services.id', 'DESC')->paginate(10);
            }else{

                 $services = $servicesByCompany->Where(function($query) {
                                 $query  ->orWhere('services.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('services.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('services.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('services.id', 'DESC')->onlyTrashed()->paginate(10);
                                       
            }
     
            if(in_array("viewServices", $this->permissions)){
                
                return view('livewire.services.service-index-company', [

                    'services' => $services,

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
        $serviceWithRolesAndPermissions = User::where('id',auth()->user()->id)->with('roles')->first();
        $serviceWithDirectsPermissions = User::where('id',auth()->user()->id)->with('permissions')->first();
        
        
        $permissions = [];

        //find permissions for roles
        foreach ($serviceWithRolesAndPermissions->roles as $key => $role) {
           
            $role = Role::where('id',$role->id)->with('permissions')->first();
                
                foreach ($role->permissions as $key => $permission) {
                    array_push($permissions,$permission->name);
                }                
        }

        //find directs permissions
        foreach ($serviceWithDirectsPermissions->permissions as $key => $permission) {
        
            array_push($permissions,$permission->name);
                         
        }

        $this->permissions = array_unique($permissions);

        //dd($this->permissions);
    }

    
    public function updatingSearch()
    {
        $this->resetPage();        
    }

    public function confirmServiceDeletion($serviceId)
    {
        $this->serviceId = $serviceId; 
        $this->deleteService = true;
    }

    public function confirmForceServiceDeletion($serviceId)
    {
        $this->serviceId = $serviceId; 
        $this->forceDeleteService = true;
    }

    public function confirmRestoreService($serviceId)
    {
        $this->serviceId = $serviceId; 
        $this->restoreService = true;
    }

    public function deleteService()
    {

        if (! Hash::check($this->passwordService, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            Service::destroy($this->serviceId);


            $this->deleteService = false;
            $this->passwordService = null;
            $this->serviceId = null;
            $this->emit("deleted");

        }       
    }

    public function forceDeleteService()
    {

        if (! Hash::check($this->passwordService, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $service = Service::withTrashed()->find($this->serviceId);
            $service->forceDelete();
            $this->forceDeleteService = false;
            $this->passwordService = null;
            $this->serviceId = null;
            $this->emit("forceDeleted");

        }       
    }

    public function restoreService()
    {

        if (! Hash::check($this->passwordService, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $service = Service::withTrashed()->find($this->serviceId);
            $service->restore();
            $this->restoreService = false;
            $this->passwordService = null;
            $this->serviceId = null;
            $this->emit("restore");

        }       
    }

 
    public function saveService()
    {
        $this->validate([
            'name' => 'required|string|max:255',             
        ]);
    

        $service = Service::create([
            'name' => $this->name, 
            'company_id' => $this->companyId,      
        ]);

        $this->name = "";
        
        $this->createNewService = false; 
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewService()
    {
        if ($this->createNewService == false) {
            $this->name = "";
        }
    }

    public function editService($id)
    {
        
        $service = Service::find($id);   
        
        $this->service = $service;
        $this->name = $service->name;        
        $this->editService = true; 
    }

    public function updateService()
    {
        $this->validate([
            'name' => 'required|string|max:255',
                         
        ]);               

        $service = Service::find($this->service->id)->update([

            'name' => $this->name,
        ]);        

        $this->name = null;     
        $this->service = null;
        $this->editService = false; 
        $this->emit('updated');
    }



    public function downloadServices()
    {
       
        return (new CompanyServicesExport(['search' => $this->search], ['companyId' => $this->companyId], ['active' => $this->active]))->download('services.xlsx'); 
       
    }

    public function showService($id)
    {
        $this->serviceShow = Service::where('id',$id)->first();
        

        $this->showService = true;
    }

    public function closeShowService()
    {
        $this->showService = false;

        $this->serviceShow = null;        
    }


    public function active($active)
    {
        
        $this->active = $active;
    }



}