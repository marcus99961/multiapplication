<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lnf extends Model
{
    use HasFactory,SoftDeletes ;
    protected $fillable = [
        'date',
        'location',
        'sr_no',
        'finder',
        'photo',
        'item_name',
        'claimed_by',
        'safe_location',
        'claimed_date',
        'item_status',
        'remark',
        'attachment',
        'guest_name'
       
    ];
   
}



