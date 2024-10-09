<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class ItemSalesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }

    protected $itemSalesAdss;
    protected $itemSalesMenu;
    protected $totalItemSoldMenu;
    protected $totalItemRefundMenu ;
    protected $totalGrossMenu;
    protected $totalDiscountMenu ;
    protected $totalRefundMenu;
    protected $totalNetMenu ;
    protected $totalItemSoldAdds;
    protected $totalItemRefundAdds ;
    protected $totalGrossAdds ;
    protected $totalDiscountAdds ;
    protected $totalRefundAdds ;
    protected $totalNetAdds ;

    public function __construct(
        $itemSalesAdss,
        $itemSalesMenu,
        $totalItemSoldMenu,
        $totalItemRefundMenu ,
        $totalGrossMenu,
        $totalDiscountMenu ,
        $totalRefundMenu,
        $totalNetMenu ,
        $totalItemSoldAdds,
        $totalItemRefundAdds ,
        $totalGrossAdds ,
        $totalDiscountAdds ,
        $totalRefundAdds ,
        $totalNetAdds ,
    )
    {
        $this->itemSalesAdss = $itemSalesAdss;
        $this->itemSalesMenu = $itemSalesMenu;
        $this->totalItemSoldMenu = $totalItemSoldMenu;
        $this->totalItemRefundMenu = $totalItemRefundMenu ;
        $this->totalGrossMenu = $totalGrossMenu;
        $this->totalDiscountMenu = $totalDiscountMenu ;
        $this->totalRefundMenu= $totalRefundMenu;
        $this->totalNetMenu = $totalNetMenu ;
        $this->totalItemSoldAdds = $totalItemSoldAdds;
        $this->totalItemRefundAdds = $totalItemRefundAdds ;
        $this->totalGrossAdds = $totalGrossAdds ;
        $this->totalDiscountAdds = $totalDiscountAdds ;
        $this->totalRefundAdds = $totalRefundAdds ;
        $this->totalNetAdds = $totalNetAdds ;
    }

    public function view(): View
    {
        return view('Report.export_report.export_ItemSales', [
            'itemSalesAdss' => $this->itemSalesAdss ,
            'itemSalesMenu' => $this->itemSalesMenu ,
            'totalItemSoldMenu' => $this->totalItemSoldMenu ,
            'totalItemRefundMenu' => $this->totalItemRefundMenu ,
            'totalGrossMenu' => $this->totalGrossMenu ,
            'totalDiscountMenu' => $this->totalDiscountMenu ,
            'totalRefundMenu' => $this->totalRefundMenu,
            'totalNetMenu' => $this->totalNetMenu ,
            'totalItemSoldAdds' => $this->totalItemSoldAdds ,
            'totalItemRefundAdds' => $this->totalItemRefundAdds ,
            'totalGrossAdds' => $this->totalGrossAdds ,
            'totalDiscountAdds' => $this->totalDiscountAdds ,
            'totalRefundAdds' => $this->totalRefundAdds ,
            'totalNetAdds' => $this->totalNetAdds ,
        ]);
    }
}
