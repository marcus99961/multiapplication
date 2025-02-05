<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable=[
        'invoice_no',
        'received_date',
        'location_id',
        'supplier_id',
        'currency',
        'item_id',
        'price_mmk',
        'price_usd',
        'qty',
        'expired_date',
        

    ];
    public function item(){
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
    public function supplier(){
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
    public function location(){
        return $this->hasOne(Location::class, 'id', 'location_id');
    }
}
