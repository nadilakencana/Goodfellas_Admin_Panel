<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PaymentMethodeExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }

    protected  $paymentData;
    protected  $totalOrders ;
    protected  $totalPembayarans ;

    public function __construct(
        $paymentData,

        $totalOrders,
        $totalPembayarans,
    )
    {
        $this->paymentData = $paymentData;
        $this->totalOrders =$totalOrders;
        $this->totalPembayarans =$totalPembayarans;

    }
    public function view(): View
    {
        return view('Report.export_report.export_paymentMetode', [
           'paymentData'=> $this->paymentData,
           'totalOrders'=> $this->totalOrders ,
           'totalPembayarans'=> $this->totalPembayarans ,

        ]);
    }
}
