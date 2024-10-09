<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class DetailTransactionItemsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
   
      protected $itemSalesMenu;

      public function __construct($itemSalesMenu)
      {
         $this->itemSalesMenu = $itemSalesMenu;
      }
        public function view(): View{
            return view('Report.export_report.export_detail_transaction', [
                'itemSalesMenu' => $this->itemSalesMenu ,
            ]);
        }
}
