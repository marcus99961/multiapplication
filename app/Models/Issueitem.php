<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issueitem extends Model
{
    use HasFactory;
    public function location(){
        return $this->hasOne(Location::class, 'id', 'location_id');
    }
    public function department(){
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
    public function item(){
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
