<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaintmessageuser extends Model
{
    use HasFactory;
    protected $fillable = [
        'complaint_id',
        'message_id',
        'user_id'
      
    ];

    protected $dates = ['created_at'];

    protected $appends = ['createdHumanReadable'];

    public function getCreatedHumanReadableAttribute()
    {
        return $this->created_at->diffForHumans();
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
