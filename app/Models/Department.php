<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
      
    ];
    protected $dates = ['created_at'];

    protected $appends = ['createdHumanReadable'];

    public function getCreatedHumanReadableAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    public function userDepartment(): HasOneThrough
    {
        return $this->hasOneThrough(Complaint::class, User::class, 'department_id','user_id','id','id' );
    }
}
