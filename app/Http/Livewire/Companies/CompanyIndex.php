<?php

namespace App\Http\Livewire\Companies;


use App\Exports\CompaniesExport;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\withMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;


class CompanyIndex extends Component
{
    use WithPagination;



    public $deleteCompany = false;
    public $companyId; 
    public $password;



    public $createNewCompany = false; 
    public $name;

    public $companyEdit;
    public $companyShow;

    
    public $editCompany = false;
    public $showCompany = false;

    public $search; 


    protected $rules = [
        'name' => 'required|string|max:70|min:1|unique:companies,name',        
    ];

    public function render()
    {
        $companiesByUser = User::find(auth()->user()->id)->companies();
        $companies = $companiesByUser->Where(function($query) {
                             $query  ->orWhere('companies.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                             })->orderBy('companies.id', 'DESC')->paginate(10);

        return view('livewire.companies.company-index', [

            'companies' => $companies,

        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();        
    }

    public function confirmCompanyDeletion($companyId)
    {
        $this->companyId = $companyId; 
        $this->deleteCompany = true;
    }

    public function deleteCompany()
    {

        if (! Hash::check($this->password, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            Company::destroy($this->companyId);
            $this->deleteCompany = false;
            $this->password = null;
            $this->companyId = null;
            $this->emit("deleted");

        }       
    }

 
    public function saveCompany()
    {
        $this->validate();
 
        Company::create([
            'name' => $this->name,
        ]);

        $this->name = "";
        $this->createNewCompany = false; 
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewCompany()
    {
        if ($this->createNewCompany == false) {
            $this->name = "";
        }
    }

    public function editCompany($id)
    {
        
        $company = Company::find($id);   
        
        $this->company = $company;
        
        $this->name = $company->name;

        $this->editCompany = true; 
    }

    public function updateCompany()
    {
        $this->validate();             

        Company::find($this->company->id)->update([

            'name' => $this->name,
        ]);        
        $this->name = null;     
        $this->company = null;
        $this->editCompany = false; 
        $this->emit('updated');
    }

    public function downloadCompanies()
    {
       
       return (new CompaniesExport($this->search))->download('companies.xlsx'); 
       
    }

    public function showCompany($id)
    {
        $this->companyShow = Company::where('id',$id)->with('users')->first();
        

        $this->showCompany = true;
    }

    public function closeShowCompany()
    {
        $this->showCompany = false;

        $this->companyShow = null;        
    }



}
