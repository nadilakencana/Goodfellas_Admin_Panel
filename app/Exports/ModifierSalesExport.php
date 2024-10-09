<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class ModifierSalesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }

   protected $itemSalesAdss;
   protected $qty ;
   protected $Gross ;
   protected $Dis ;
   protected $ref ;
   protected $netSels;

   public function __construct(
        $itemSalesAdss,
        $qty ,
        $Gross ,
        $Dis ,
        $ref ,
        $netSels,
   )
   {
        $this->itemSalesAdss =$itemSalesAdss;
        $this->qty =$qty ;
        $this->Gross =$Gross;
        $this->Dis =$Dis ;
        $this->ref =$ref ;
        $this->netSels =$netSels;
   }

   public function view() : View
   {
     return view('Report.export_report.export_modifier', [

      'itemSalesAdss' => $this->itemSalesAdss ,
      'qty' => $this->qty ,
      'Gross' => $this->Gross ,
      'Dis' => $this->Dis ,
      'ref' => $this->ref ,
      'netSels' => $this->netSels ,
     ]);
   }

}
