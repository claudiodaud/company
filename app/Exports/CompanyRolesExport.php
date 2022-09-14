<?php

namespace App\Exports;


use App\Models\Company;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyRolesExport implements FromView
{
    use Exportable;

    protected $search;
    protected $companyId;
    protected $active;

    public function __construct($search,$companyId,$active)
    {

        $this->search = $search['search'];
        $this->companyId = $companyId['companyId'];
        $this->active = $active['active'];
        


    }
    public function view(): View
    {
        
        if ($this->search != null) {
            $rolesByCompany = Company::find($this->companyId)->roles();
            
            if ($this->active == true) {

                $roles = $rolesByCompany->Where(function($query) {
                                 $query  ->orWhere('roles.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('roles.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('roles.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('roles.id', 'DESC')->get();
            }else{

                 $roles = $rolesByCompany->Where(function($query) {
                                 $query  ->orWhere('roles.name', 'like', '%'.$this->search.'%')                                         
                                         ->orWhere('roles.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('roles.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('roles.id', 'DESC')->onlyTrashed()->get();
                                       
            }
            
            
            return view('exports.RoleExport', [
            'roles' => $roles,
            ]); 

        }else{

             if ($this->active == true) {

                $roles = Company::find($this->companyId)->roles()->orderBy('roles.id', 'DESC')->get();

            }else{

                $roles = Company::find($this->companyId)->roles()->orderBy('roles.id', 'DESC')->onlyTrashed()->get();

            }    
            
            return view('exports.RoleExport', [
            'roles' => $roles,
            ]);
        }
    }
}
