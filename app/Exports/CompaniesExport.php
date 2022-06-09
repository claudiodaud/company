<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CompaniesExport implements FromView
{
    use Exportable;

    protected $search;

    public function __construct($search)
    {

        $this->search = $search;

    }
    public function view(): View
    {
        

        if ($this->search != null) {
            $companiesByUser = User::find(auth()->user()->id)->companies();
            
            $companies = $companiesByUser->Where(function($query) {
                             $query  ->orWhere('companies.name', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.created_at', 'like', '%'.$this->search.'%')
                                     ->orWhere('companies.updated_at', 'like', '%'.$this->search.'%');                            
                             })->orderBy('companies.id', 'DESC')->get();

            return view('exports.CompaniesExport', [
            'companies' => $companies,
            ]); 
        }else{
           return view('exports.CompaniesExport', [
            'companies' => User::find(auth()->user()->id)->companies()->orderBy('companies.id', 'DESC')->get(),
            ]); 
        }
    }
}
