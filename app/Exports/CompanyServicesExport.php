<?php

namespace App\Exports;

use App\Models\Service;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyServicesExport implements FromView
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
            $servicesByCompany = Company::find($this->companyId)->services();
            
            if ($this->active == true) {

                $services = $servicesByCompany->Where(function($query) {
                                 $query  ->orWhere('services.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('services.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('services.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('services.id', 'DESC')->get();
            }else{

                 $services = $servicesByCompany->Where(function($query) {
                                 $query  ->orWhere('services.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('services.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('services.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('services.id', 'DESC')->onlyTrashed()->get();
                                       
            }            
            
            return view('exports.ServicesExport', [
            'services' => $services,
            ]); 
            
        }else{

             if ($this->active == true) {

                $services = Company::find($this->companyId)->services()->orderBy('services.id', 'DESC')->get();

            }else{

                $services = Company::find($this->companyId)->services()->orderBy('services.id', 'DESC')->onlyTrashed()->get();

            }    
            
            return view('exports.ServicesExport', [
                
            'services' => $services,
            
            ]); 
        }
    }
}
