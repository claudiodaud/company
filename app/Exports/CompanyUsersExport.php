<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyUsersExport implements FromView
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
            $usersByCompany = Company::find($this->companyId)->users();
            
            if ($this->active == true) {

                $users = $usersByCompany->Where(function($query) {
                                 $query  ->orWhere('users.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('users.email', 'like', '%'.$this->search.'%')   
                                         ->orWhere('users.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('users.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('users.id', 'DESC')->get();
            }else{

                 $users = $usersByCompany->Where(function($query) {
                                 $query  ->orWhere('users.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('users.email', 'like', '%'.$this->search.'%')   
                                         ->orWhere('users.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('users.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('users.id', 'DESC')->onlyTrashed()->get();
                                       
            }
            
            
            return view('exports.UsersExport', [
            'users' => $users,
            ]); 
        }else{

             if ($this->active == true) {

                $users = Company::find($this->companyId)->users()->orderBy('users.id', 'DESC')->get();

            }else{

                $users = Company::find($this->companyId)->users()->orderBy('users.id', 'DESC')->onlyTrashed()->get();

            }    
            
            return view('exports.UsersExport', [
            'users' => $users,
            
            ]); 
        }
    }
}

