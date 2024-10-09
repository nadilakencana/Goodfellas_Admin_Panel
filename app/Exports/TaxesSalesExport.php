<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class TaxesSalesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }
    protected $dataTax;
    protected $totalTax;

    public function __construct( $dataTax, $totalTax,)
    {
        $this-> dataTax   = $dataTax;
        $this-> totalTax  = $totalTax;
    }

    public function view() : View
    {
        return view('Report.export_report.export_Taxes', [
           'dataTax' => $this-> dataTax  ,
           'totalTax' => $this-> totalTax ,
        ]);
    }
}
