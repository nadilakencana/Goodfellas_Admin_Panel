<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivity extends Model
{
    use HasFactory;
    protected $table = 'activity';
    protected $fillable =[
        'id_admin',
        'keterangan',
    ];

    public $timestamps = true;

    public function admin(){
        return $this->belongsTo(Admin::class, 'id_admin','id');
    }
}
