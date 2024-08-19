<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaintroom extends Model
{
    use HasFactory;
    protected $fillable = [
        'complaint_id',
        'room_id'
      
    ];
}
