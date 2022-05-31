<?php

namespace App\Exports;

use App\Models\Company;
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
            return view('exports.CompaniesExport', [
            'companies' => Company::Where('name', 'like', '%'.$this->search.'%')
                           ->orWhere('created_at', 'like', '%'.$this->search.'%')
                           ->orWhere('updated_at', 'like', '%'.$this->search.'%')
                           ->orderBy('id', 'DESC')
                           ->get(),
            ]); 
        }else{
           return view('exports.CompaniesExport', [
            'companies' => Company::all(),
            ]); 
        }
    }
}
