<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additional_menu_detail extends Model
{
    use HasFactory;
    protected $table = 'additional_menu';
    protected $fillable = [
        'id_detail_order',
        'id_option_additional',
        'qty',
        'total'

    ];

    public $timestamps = true;

    public function detail_order(){
            return $this->belongsTo(DetailOrder::class, 'id_detail_order', 'id');

    }

    public function optional_Add(){
        return $this->belongsTo(OptionModifier::class, 'id_option_additional', 'id');
    }

}

