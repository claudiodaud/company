<?php

namespace App\Exports;

use App\Models\Service;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyConditionsExport implements FromView
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
            $conditionsByCompany = Company::find($this->companyId)->conditions();
            
            if ($this->active == true) {

                $conditions = $conditionsByCompany->Where(function($query) {
                                 $query  ->orWhere('conditions.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('conditions.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('conditions.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('conditions.id', 'DESC')->get();
            }else{

                 $conditions = $conditionsByCompany->Where(function($query) {
                                 $query  ->orWhere('conditions.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('conditions.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('conditions.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('conditions.id', 'DESC')->onlyTrashed()->get();
                                       
            }            
            
            return view('exports.ConditionsExport', [

            'conditions' => $conditions,
            
            ]); 
            
        }else{

             if ($this->active == true) {

                $conditions = Company::find($this->companyId)->conditions()->orderBy('conditions.id', 'DESC')->get();

            }else{

                $conditions = Company::find($this->companyId)->conditions()->orderBy('conditions.id', 'DESC')->onlyTrashed()->get();

            }    
            
            return view('exports.ConditionsExport', [
                
            'conditions' => $conditions,
            
            ]); 
        }
    }
}
