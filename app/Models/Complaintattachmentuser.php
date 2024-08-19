<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaintattachmentuser extends Model
{
    use HasFactory;
    protected $fillable = [
        'complaint_id',
        'attachment_id',
        'user_id'
      
    ];
}
