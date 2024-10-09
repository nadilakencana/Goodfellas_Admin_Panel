<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class ReportSalesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }

    protected  $allGrandSales;
    protected  $allGrandDis;
    protected  $allGrandRefund;
    protected  $allGrandNet;
    protected  $totalTax;
    protected  $TotalGrand;

    public function __construct(
        $allGrandSales,
        $allGrandDis,
        $allGrandRefund,
        $allGrandNet,
        $totalTax,
        $TotalGrand,
    )
    {
       $this->allGrandSales = $allGrandSales;
       $this->allGrandDis = $allGrandDis;
       $this->allGrandRefund = $allGrandRefund;
       $this->allGrandNet = $allGrandNet;
       $this->totalTax = $totalTax;
       $this->TotalGrand = $TotalGrand;
    }
     public function view(): View
    {
        return view('Report.export_report.export_salesSummary', [
            'allGrandSales' => $this->allGrandSales,
            'allGrandDis' => $this->allGrandDis,
            'allGrandRefund' => $this->allGrandRefund,
            'allGrandNet' => $this->allGrandNet,
            'totalTax' => $this->totalTax,
            'TotalGrand' => $this->TotalGrand,
        ]);
    }
}
