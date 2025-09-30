<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuResep extends Model
{
    use HasFactory;
    
    protected $table = 'menu_resep';
    
    protected $fillable = [
        'id_menu',
        'id_bahan_baku'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku', 'id');
    }
}