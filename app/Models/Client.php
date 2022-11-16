<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function campaigns(){
        return $this->belongsToMany(Campaign::class);
    }

    public function brands(){
        return $this->hasMany(Brand::class,'client_id');
    }
}
