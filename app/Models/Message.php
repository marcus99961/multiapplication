<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $dates = ['created_at'];
    protected $appends = ['createdHumanReadable'];
    public function getCreatedHumanReadableAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
