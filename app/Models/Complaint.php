<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class Complaint extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'arrival',
        'departure',
        'guest_name',
        'defect',
        'action',
        'result',
        'remark',
        'user_id',
        'department_id',
        'category_id',
        'priority',
        'source',
        'status'
      
    ];
    protected $dates = ['created_at'];
    protected $appends = ['createdHumanReadable'];
    public function getCreatedHumanReadableAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


}
