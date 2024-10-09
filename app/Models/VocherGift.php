<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocherGift extends Model
{
    use HasFactory;
    protected $table = 'vocher_gift';
    protected $fillable = ['nama_vocher','slug_vocher','detail','term_condition', 'image', 'point_reward'];
    public $timestamps = true;

}
