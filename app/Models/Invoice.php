<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable=[
        'post_status',
      
        

    ];

    
    public function supplier(){
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
    public function location(){
        return $this->hasOne(Location::class, 'id', 'location_id');
    }
}
