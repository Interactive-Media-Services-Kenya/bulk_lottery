<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkMessage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function senderName() {
        return $this->belongsTo(SenderName::class,'sender_id');
    }


    public function bulkResponse(){
        return $this->hasOne(BulkResponse::class,'message_id');
    }
}
