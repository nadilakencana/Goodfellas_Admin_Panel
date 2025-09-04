<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupModifier extends Model
{
    use HasFactory;
    protected $table = 'group_modifier';

    protected $guarded = [];

    public $timestamps = true;

    public function OptionModifier(){
        return $this->hasMany(OptionModifier::class, 'id_group_modifier', 'id');
    }
}
