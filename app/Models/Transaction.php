<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=[
        'transaction_no',
        'date',      
        'currency',
        'location_id',
        'item_id',
        'group_code',
        'in_mmk',
        'in_usd',
        'in_qty',
        'out_qty',
        'out_mmk',
        'out_usd',
        'action',
        

    ];
    public function item(){
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
    public function location(){
        return $this->hasOne(Location::class, 'id', 'location_id');
    }
    public function category(){
        return $this->hasOne(Category::class, 'group_code', 'group_code');
    }
}
