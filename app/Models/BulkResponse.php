<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkResponse extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bulkMessage(){
        return $this->belongsTo(BulkMessage::class,'message_id');
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function bulkStatus(){
        return $this->hasOne(BulkStatus::class,'correlator');
    }
}
