<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issueno extends Model
{
    use HasFactory;
    protected $fillable=[
        'post_status',
      
        

    ];
    public function location(){
        return $this->hasOne(Location::class, 'id', 'location_id');
    }
    public function department(){
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
    
}
