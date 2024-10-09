<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class CategoryExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }

   protected $kategori;
   protected $modifier;
   protected $totalItemSoldMenu;
   protected $totalItemRefundMenu;
   protected $totalGrossMenu;
   protected $totalDiscountMenu;
   protected $totalRefundMenu;
   protected $totalNetMenu ;
   protected $totalItemSoldAdds;
   protected $totalItemRefundAdds ;
   protected $totalGrossAdds ;
   protected $totalDiscountAdds ;
   protected $totalRefundAdds ;
   protected $totalNetAdds ;
   protected $totalNominalKat;

   public function __construct(
        $kategori,
        $modifier,
        $totalItemSoldMenu,
        $totalItemRefundMenu,
        $totalGrossMenu,
        $totalDiscountMenu ,
        $totalRefundMenu ,
        $totalNetMenu ,
        $totalItemSoldAdds ,
        $totalItemRefundAdds ,
        $totalGrossAdds ,
        $totalDiscountAdds ,
        $totalRefundAdds ,
        $totalNetAdds ,
        $totalNominalKat,
    ){
        $this-> kategori = $kategori;
        $this-> modifier = $modifier;
        $this-> totalItemSoldMenu = $totalItemSoldMenu;
        $this-> totalItemRefundMenu = $totalItemRefundMenu;
        $this-> totalGrossMenu = $totalGrossMenu;
        $this-> totalDiscountMenu= $totalDiscountMenu;
        $this-> totalRefundMenu= $totalRefundMenu;
        $this-> totalNetMenu = $totalNetMenu ;
        $this-> totalItemSoldAdds = $totalItemSoldAdds;
        $this-> totalItemRefundAdds= $totalItemRefundAdds ;
        $this-> totalGrossAdds = $totalGrossAdds ;
        $this-> totalDiscountAdds = $totalDiscountAdds ;
        $this-> totalRefundAdds = $totalRefundAdds ;
        $this-> totalNetAdds = $totalNetAdds ;
        $this-> totalNominalKat = $totalNominalKat;
    }

    public function view() : View
    {
        return view('Report.export_report.export_category', [
            'kategori' => $this-> kategori ,
            'modifier' => $this-> modifier ,
            'totalItemSoldMenu' => $this-> totalItemSoldMenu ,
            'totalItemRefundMenu' => $this-> totalItemRefundMenu ,
            'totalGrossMenu' => $this-> totalGrossMenu ,
            'totalDiscountMenu' => $this-> totalDiscountMenu,
            'totalRefundMenu' => $this-> totalRefundMenu,
            'totalNetMenu' => $this-> totalNetMenu ,
            'totalItemSoldAdds' => $this-> totalItemSoldAdds ,
            'totalItemRefundAdds' => $this-> totalItemRefundAdds,
            'totalGrossAdds' => $this-> totalGrossAdds ,
            'totalDiscountAdds' => $this-> totalDiscountAdds ,
            'totalRefundAdds' => $this-> totalRefundAdds ,
            'totalNetAdds' => $this-> totalNetAdds ,
            'totalNominalKat' => $this-> totalNominalKat ,
        ]);
    }
}
