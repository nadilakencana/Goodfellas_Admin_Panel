<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\TextUI\XmlConfiguration\Group;

class OptionModifier extends Model
{
    use HasFactory;
    protected $table = 'option_modifier';

    protected $guarded = [];

    public $timestamps = true;

    public function groupModif(){
        return $this->belongsTo(GroupModifier::class,'id_group_modifier','id');
    }
    public function additionalAddsItem(){
        return $this->hasMany(Additional_menu_detail::class,'id_option_additional','id');
    }

}
