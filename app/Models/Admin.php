<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\Model;

class Admin extends EloquentUser
{
    use HasFactory;
    protected $table = 'admin';
    protected $fillable =[
        'nama',
        'email',
        'password',
        'id_level',
        // 'image',
    ];

    public $timestamps = false;
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];

    public function level(){
        return $this->belongsTo(Level::class, 'id_level', 'id');
    }
}
