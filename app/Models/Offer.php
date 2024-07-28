<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['client_id', 'service', 'service_price', 'quantity', 'total_price'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

