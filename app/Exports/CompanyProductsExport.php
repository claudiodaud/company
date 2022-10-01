<?php

namespace App\Exports;

use App\Models\Service;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyProductsExport implements FromView
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
            $productsByCompany = Company::find($this->companyId)->products();
            
            if ($this->active == true) {

                $products = $productsByCompany->Where(function($query) {
                                 $query  ->orWhere('products.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('products.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('products.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('products.id', 'DESC')->get();
            }else{

                 $products = $productsByCompany->Where(function($query) {
                                 $query  ->orWhere('products.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('products.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('products.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('products.id', 'DESC')->onlyTrashed()->get();
                                       
            }            
            
            return view('exports.ProductsExport', [

            'products' => $products,
            
            ]); 
            
        }else{

             if ($this->active == true) {

                $products = Company::find($this->companyId)->products()->orderBy('products.id', 'DESC')->get();

            }else{

                $products = Company::find($this->companyId)->products()->orderBy('products.id', 'DESC')->onlyTrashed()->get();

            }    
            
            return view('exports.ProductsExport', [
                
            'products' => $products,
            
            ]); 
        }
    }
}
