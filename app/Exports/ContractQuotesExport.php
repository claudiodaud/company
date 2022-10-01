<?php

namespace App\Exports;

use App\Models\Contract;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ContractQuotesExport implements FromView
{
    use Exportable;

    protected $search;
    protected $contractId;
    protected $active;

    public function __construct($search,$contractId,$active)
    {

        $this->search = $search['search'];
        $this->contractId = $contractId['contractId'];
        $this->active = $active['active'];
        


    }
    public function view(): View
    {
        
        if ($this->search != null) {
            $quotesByContract = Contract::find($this->contractId)->quotes();
            
            if ($this->active == true) {

                $quotes = $quotesByContract->Where(function($query) {
                                 $query  ->orWhere('quotes.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('quotes.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('quotes.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('quotes.id', 'DESC')->get();
            }else{

                 $quotes = $quotesByContract->Where(function($query) {
                                 $query  ->orWhere('quotes.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('quotes.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('quotes.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('quotes.id', 'DESC')->onlyTrashed()->get();
                                       
            }            
            
            return view('exports.QuotesExport', [

            'quotes' => $quotes,

            ]); 
            
        }else{

             if ($this->active == true) {

                $quotes = Contract::find($this->contractId)->quotes()->orderBy('quotes.id', 'DESC')->get();

            }else{

                $quotes = Contract::find($this->contractId)->quotes()->orderBy('quotes.id', 'DESC')->onlyTrashed()->get();

            }    
            
            return view('exports.QuotesExport', [
                
            'quotes' => $quotes,
            
            ]); 
        }
    }
}