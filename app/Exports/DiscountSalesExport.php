<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class DiscountSalesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }
    protected $dataDiscount;
    protected $count;
    protected $Gross;
    protected $ref;
    protected $netSels;

    public function __construct(
        $dataDiscount,
        $count,
        $Gross,
        $ref,
        $netSels,
    )
    {
        $this->dataDiscount = $dataDiscount;
        $this->count = $count;
        $this->Gross = $Gross;
        $this->ref = $ref;
        $this->netSels = $netSels;
    }
    public function view(): View
    {
        return view('Report.export_report.export_discount', [
               'dataDiscount' => $this->dataDiscount ,
               'count' => $this->count ,
               'Gross' => $this->Gross ,
               'ref' => $this->ref ,
               'netSels' => $this->netSels ,
        ]);
    }
}
