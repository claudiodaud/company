<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyCustomersExport implements FromView
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
            $customersByCompany = Company::find($this->companyId)->customers();
            
            if ($this->active == true) {

                $customers = $customersByCompany->Where(function($query) {
                                 $query  ->orWhere('companies.social_name', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.fantasy_name', 'like', '%'.$this->search.'%')   
                                         ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('companies.id', 'DESC')->get();
            }else{

                 $customers = $customersByCompany->Where(function($query) {
                                 $query  ->orWhere('companies.social_name', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.fantasy_name', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('companies.id', 'DESC')->onlyTrashed()->get();
                                       
            }
            
            
            return view('exports.CustomersExport', [
            'customers' => $customers,
            ]); 

        }else{

             if ($this->active == true) {

                $customers = Company::find($this->companyId)->customers()->orderBy('companies.id', 'DESC')->get();

            }else{

                $customers = Company::find($this->companyId)->customers()->orderBy('companies.id', 'DESC')->onlyTrashed()->get();

            }    
            
            return view('exports.CustomersExport', [
            'customers' => $customers,
            ]);
        }
    }
}
