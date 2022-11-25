<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkStatus extends Model
{
    protected $connection= 'mysql2';

    protected $table = 'dlr_bulk';
    use HasFactory;

    public function correlator(){
        return $this->belongsTo(BulkResponse::class,'correlator');
    }
}
