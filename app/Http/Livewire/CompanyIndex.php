<?php

namespace App\Http\Livewire;


use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\withMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use App\Exports\CompaniesExport;


class CompanyIndex extends Component
{
    use WithPagination;



    public $deleteCompany = false;
    public $companyId; 
    public $password;



    public $createNewCompany = false; 
    public $name;

    public $company;
    

    
    public $editCompany = false;

    public $search; 


    protected $rules = [
        'name' => 'required|string|max:70|min:1|unique:companies,name',        
    ];

    public function render()
    {
        $companies = Company::orWhere('name', 'like', '%'.$this->search.'%')
           ->orWhere('created_at', 'like', '%'.$this->search.'%')
           ->orWhere('updated_at', 'like', '%'.$this->search.'%')
           ->orderBy('id', 'DESC')
           ->paginate(10);

        
        return view('livewire.company-index', [

            'companies' => $companies,

        ]);
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
        
       
        $this->name = "";
        $this->company = "";
        $this->editCompany = false; 
        $this->emit('updated');
    }

    public function downloadCompanies()
    {
       
       return (new CompaniesExport($this->search))->download('companies.xlsx'); 
       
    }

}
