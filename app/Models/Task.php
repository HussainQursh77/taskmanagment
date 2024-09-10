<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Task extends Model
{
    use HasFactory;


    protected $table = 'user_tasks';


    protected $primaryKey = 'assigned_to';


    public $timestamps = true;


    const CREATED_AT = 'create_on';
    const UPDATED_AT = 'updated_on';
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'assigned_to'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }


    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }


    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat('Y-m-d', $value);
    }

    public function scopePriority($query, $priority)
    {
        if (!empty($priority)) {
            return $query->where('priority', $priority);
        }
        return $query;
    }

    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            return $query->where('status', $status);
        }
        return $query;
    }
}
