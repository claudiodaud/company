<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    use Exportable;

    protected $search;
    protected $companyId;

    public function __construct($search,$companyId)
    {

        $this->search = $search['search'];
        $this->companyId = $companyId['companyId'];
        


    }
    public function view(): View
    {
        
        if ($this->search != null) {
            $usersByCompany = Company::find($this->companyId)->users();
            
            $users = $usersByCompany->Where(function($query) {
                             $query  ->orWhere('users.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.email', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('users.updated_at', 'like', '%'.$this->search.'%');                            
                             })->orderBy('users.id', 'DESC')->get();
            
            return view('exports.UsersExport', [
            'users' => $users,
            ]); 
        }else{
           return view('exports.UsersExport', [
            'users' => Company::find($this->companyId)->users()->orderBy('users.id', 'DESC')->get(),
            
            ]); 
        }
    }
}

