<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRTable extends Model
{
    use HasFactory;

    protected $table = 'qr_table';
    protected $fillable= [
        'meja',
        'link'
    ];
}
