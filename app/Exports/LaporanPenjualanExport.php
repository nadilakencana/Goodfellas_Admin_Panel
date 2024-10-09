<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Menu;
use App\Models\Additional_menu_detail;
use App\Models\Discount_detail_order;
use App\Models\Orders;
use App\Models\AdditionalRefund;
use App\Models\RefundOrderMenu;
use App\Models\DetailOrder;
use App\Models\DiscountMenuRefund;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LaporanPenjualanExport implements  FromView
{
    // use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }
    protected $menu;
    protected $tanggal_mulai;
    protected $tanggal_akhir;
    protected $items;
    protected $itemsold;
    protected $totalDiscount;
    protected $harga;
    protected $totalRefund;
    protected $netSales;
    protected $varian;
    protected $additional;
    protected $itmAdsSold;
    protected $refundSum;
    protected $refundDisCountSum;
    protected $refund;
    protected $grosSale;
    protected $NetSales;


     public function __construct(
         $menu,
         $tanggal_mulai,
         $tanggal_akhir,
         $items,
         $itemsold,
         $totalDiscount,
         $harga,
         $totalRefund,
         $netSales,
         $varian,
         $additional,
         $itmAdsSold,
         $refundSum,
         $refundDisCountSum,
         $refund,
         $grosSale,
         $NetSales,
      )
    {
        $this->menu = $menu;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->items = $items;
        $this->itemsold = $itemsold;
        $this->totalDiscount = $totalDiscount;
        $this->harga = $harga;
        $this->totalRefund = $totalRefund;
        $this->netSales = $netSales;
        $this->varian =  $varian;
        $this->additional= $additional;
        $this->itmAdsSold= $itmAdsSold;
        $this->refundSum= $refundSum;
        $this->refundDisCountSum= $refundDisCountSum;
        $this->refund= $refund;
        $this->grosSale= $grosSale;
        $this->NetSales= $NetSales;
    }

    public function view(): View
    {
        return view('Orders.export_data', [
            'menu' => $this->menu,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_akhir' => $this->tanggal_akhir,
            'items' => $this->items,
            'itemsold' => $this->itemsold,
            'totalDiscount' => $this->totalDiscount,
            'harga' => $this->harga,
            'totalRefund' => $this->totalRefund,
            'netSales' => $this->netSales,
            'varian' => $this->varian,
            'additional' => $this->additional,
            'itmAdsSold'  => $this->itmAdsSold,
            'refundSum' => $this->refundSum,
            'refundDisCountSum' => $this->refundDisCountSum,
            'refund' => $this->refund,
            'grosSale' => $this->grosSale,
            'NetSales' => $this->NetSales,
        ]);
    }
}
