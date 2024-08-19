<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaintmember extends Model
{
    use HasFactory;
    protected $fillable = [
        'complaint_id',
        'member_id'
      
    ];
}
