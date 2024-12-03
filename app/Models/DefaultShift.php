<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'clock_in',
        'clock_out',
        'day_of_week', 
    ];

    public function employee()
    {
        return $this->belongsTo(employee::class, 'employee_id');
    }
}
