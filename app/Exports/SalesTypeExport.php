<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class SalesTypeExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // public function collection()
    // {
    //     //
    // }
        protected $SalesData;
        protected $totalOrders;
        protected $totalPembayarans;

    public function __construct(
        $SalesData,
        $totalOrders,
        $totalPembayarans,
    )
    {
        $this->SalesData = $SalesData;
        $this->totalOrders = $totalOrders;
        $this->totalPembayarans = $totalPembayarans;
    }

    public function view(): View
    {
        return view('Report.export_report.export_SalesType', [
            'SalesData' => $this->SalesData ,
            'totalOrders' => $this->totalOrders ,
            'totalPembayarans' => $this->totalPembayarans ,
        ]);
    }
}
