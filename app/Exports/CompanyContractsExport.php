<?php

namespace App\Exports;


use App\Models\Company;
use App\Models\Contract;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyContractsExport implements FromView
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
            $contractsByCompany = Company::find($this->companyId)->contracts();
            
            if ($this->active == true) {

                $contracts = $contractsByCompany->Where(function($query) {
                                 $query  ->orWhere('contracts.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('contracts.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('contracts.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('contracts.id', 'DESC')->get();
            }else{

                 $contracts = $contractsByCompany->Where(function($query) {
                                 $query  ->orWhere('contracts.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('contracts.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('contracts.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('contracts.id', 'DESC')->onlyTrashed()->get();
                                       
            }
            
            
            return view('exports.ContractExport', [
            'contracts' => $contracts,
            ]); 

        }else{

             if ($this->active == true) {

                $contracts = Company::find($this->companyId)->contracts()->orderBy('contracts.id', 'DESC')->get();

            }else{

                $contracts = Company::find($this->companyId)->contracts()->orderBy('contracts.id', 'DESC')->onlyTrashed()->get();

            }    
            
            return view('exports.ContractExport', [
            'contracts' => $contracts,
            ]);
        }
    }
}
