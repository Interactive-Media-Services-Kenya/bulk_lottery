<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDepartment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function  client(){
        return $this->belongsTo(Client::class);
    }
    public function users(){
        return $this->hasMany(User::class,'client_department_id');
    }
}
